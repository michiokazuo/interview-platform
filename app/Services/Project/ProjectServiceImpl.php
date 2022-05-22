<?php

namespace App\Services\Project;

use App\Models\Project;
use App\Models\RecruitmentNews;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class ProjectServiceImpl implements ProjectService
{
    protected $_repository, $newsRepository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(Project::class);
        $this->newsRepository = app()->make(RecruitmentNews::class);
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, array $data)
    {
        try {
            if ($user->company_id) {
                $project = $this->_repository->create([
                    'company_id' => $user->company_id,
                    'title' => $data['title'],
                    'start_time' => now(),
                    'description' => $data['description'],
                    'status' => 'Created'
                ]);

                if ($project) {
                    return $project;
                }
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function findById(User $user, int $project_id)
    {
        try {
            if ($user->company_id) {
                $project = $this->_repository->find($project_id);

                if ($project && $project->company_id == $user->company_id) {
                    return $project;
                }
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function showAll(User $user, int $per_page = 8)
    {
        try {
            $project = $this->_repository->where('company_id', $user->company_id)
                ->orderBy('created_at', 'desc')->paginate($per_page);

            if ($project) {
                return [
                    'current_page' => $project->currentPage(),
                    'data' => $project->items(),
                    'total' => $project->total(),
                    'last_page' => $project->lastPage(),
                ];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(User $user, array $data, int $project_id)
    {
        try {
            $project = $this->_repository->find($project_id);

            if ($project && $project->company_id == $user->company_id) {
                $project->update([
                    'title' => $data['title'],
                    'description' => $data['description'],
//                    'status' => 'Created'
                ]);

                return $this->findById($user, $project_id);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(User $user, int $project_id): bool
    {
        try {
            $project = $this->_repository->find($project_id);

            if ($project && $project->company->id == $user->company_id) {
                $project->delete();
                return true;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function changeStatus(User $user, int $project_id)
    {
        try {
            $project = $this->_repository->find($project_id);

            if ($project && $project->company->id == $user->company->id) {
                $project->update([
                    'end_time' => $project->end_time ? null : now(),
//                    'status' => 'In progress'
                ]);
                
                if($project->end_time){
                    $this->newsRepository->where([
                        ['project_id', $project->id],
                        ['end_time', null]
                    ])->update(['end_time' => now()]);
                }

                return $this->findById($user, $project_id);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
