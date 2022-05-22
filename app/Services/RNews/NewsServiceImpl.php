<?php

namespace App\Services\RNews;

use App\Models\RecruitmentNews;
use App\Models\User;
use App\Services\Project\ProjectService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class NewsServiceImpl implements NewsService
{
    protected $_repository, $projectService, $userRepository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(ProjectService $projectService)
    {
        $this->_repository = app()->make(RecruitmentNews::class);
        $this->projectService = $projectService;
        $this->userRepository = app()->make(User::class);
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, array $data)
    {
        try {
            $company_id = $user->company_id;
            $project = null;

            if ($data['project_id'] && $company_id) {
                $project = $this->projectService->findById($user, $data['project_id']);
            }

            if ($project && $project->company_id == $company_id) {
                $news = $this->_repository->create([
                    'title' => $data['title'],
                    'benefits' => $data['benefits'],
                    'description' => $data['description'],
                    'start_time' => now(),
                    'salary' => $data['salary'],
                    'job_position' => $data['job_position'],
                    'working_form' => $data['working_form'],
                    'gender' => $data['gender'],
                    'experience' => $data['experience'],
                    'workplace' => $data['workplace'],
                    'number_of_recruits' => $data['number_of_recruits'],
                    'requirements' => $data['requirements'] ?? null,
                    'project_id' => $project->id,
                    'company_id' => $company_id,
                ]);

                if ($news) {
                    return $news;
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
    public function findById(User $user, int $news_id)
    {
        try {
            $news = $this->_repository->find($news_id);

            if ($news) {
                return $news;
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
            $news = $this->_repository->where('end_time', null)
                ->orderBy('start_time', 'desc')->paginate($per_page);

            if ($news) {
                return [
                    'current_page' => $news->currentPage(),
                    'data' => $news->items(),
                    'total' => $news->total(),
                    'last_page' => $news->lastPage(),
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
    public function showAllByUser(User $user, int $company_id, int $per_page = 8)
    {
        try {
            $userOwner = $this->userRepository->where('company_id', $company_id)->first(); 
            $news = $this->_repository->where('company_id', $company_id)
                ->orderBy('created_at', 'desc')->paginate($per_page);

            if ($news && $userOwner) {
                return [
                    'current_page' => $news->currentPage(),
                    'data' => $news->items(),
                    'total' => $news->total(),
                    'last_page' => $news->lastPage(),
                    'user' => $userOwner
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
    public function update(User $user, array $data, int $news_id)
    {
        try {
            $news = $this->_repository->find($news_id);

            if ($news && $news->project->company_id == $user->company_id) {
                $news->update([
                    'title' => $data['title'],
                    'benefits' => $data['benefits'],
                    'description' => $data['description'],
                    'salary' => $data['salary'],
                    'job_position' => $data['job_position'],
                    'working_form' => $data['working_form'],
                    'gender' => $data['gender'],
                    'experience' => $data['experience'],
                    'workplace' => $data['workplace'],
                    'number_of_recruits' => $data['number_of_recruits'],
                    'requirements' => $data['requirements'] ?? null,
                ]);

                return $this->findById($user, $news_id);
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
    public function delete(User $user, int $news_id): bool
    {
        try {
            $news = $this->_repository->find($news_id);

            if ($news && $news->company_id == $user->company_id) {
                $news->delete();

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
    public function changeStatus(User $user, int $news_id)
    {
        try {
            $news = $this->_repository->find($news_id);

            if ($news && $news->company_id == $user->company_id) {
                $news->update([
                    'end_time' => $news->end_time ? null : now(),
//                    'status' => 'In progress'
                ]);

                return $this->findById($user, $news_id);
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
    public function showAllByProject(User $user, int $project_id, int $per_page = 8)
    {
        try {
            $news = $this->_repository->where('project_id', $project_id)
                ->orderBy('created_at', 'desc')->get();

            $project = $this->projectService->findById($user, $project_id);

            if ($news && $project && $project->company_id == $user->company_id) {
                return $news;
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
