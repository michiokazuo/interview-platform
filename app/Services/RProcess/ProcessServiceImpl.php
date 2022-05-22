<?php

namespace App\Services\RProcess;

use App\Models\RecruitmentProcess;
use App\Models\User;
use App\Services\Project\ProjectService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class ProcessServiceImpl implements ProcessService
{
    protected $_repository, $projectService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(ProjectService $projectService)
    {
        $this->_repository = app()->make(RecruitmentProcess::class);
        $this->projectService = $projectService;
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
                $process_prev = $this->_repository->where([
                    ['project_id', $project->id],
                    ['next_step', null],
                ])->first();

                $process = $this->_repository->create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'start_time' => now(),
                    'project_id' => $project->id,
                ]);

                if ($process_prev) {
                    $prev_step = $process_prev->id;
                    $process_prev->next_step = $process->id;
                    $process_prev->save();

                    $process->prev_step = $prev_step;
                    $process->save();
                }


                if ($process) {
                    return $process;
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
    public function findById(User $user, $process_id)
    {
        try {
            if ($user->company_id) {
                $process = $this->_repository->find($process_id);

                if ($process && $process->project->company_id == $user->company_id) {
                    return $process;
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
    public function showAll(User $user, int $project_id)
    {
        try {
            $company_id = $user->company_id;
            $project = null;

            if ($project_id && $company_id) {
                $project = $this->projectService->findById($user, $project_id);
            }

            if ($project && $project->company_id == $company_id) {
                $processList = $this->_repository->where('project_id', $project_id)
                    ->orderBy('prev_step', 'asc')->get();

                if ($processList) {
                    return $processList;
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
    public function update(User $user, array $data, int $process_id)
    {
        try {
            $process = $this->_repository->find($process_id);

            if ($process && $process->project->company_id == $user->company_id) {
                $process->update([
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                return $this->findById($user, $process_id);
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
    public function delete(User $user, int $process_id): bool
    {
        try {
            $process = $this->_repository->find($process_id);

            if ($process && $process->project->company_id == $user->company_id) {
                $prev = $this->_repository->find($process->prev_step);
                $next = $this->_repository->find($process->next_step);

                if ($prev) {
                    $prev->next_step = $next->id ?? null;
                    $prev->save();
                }

                if ($next) {
                    $next->prev_step = $prev->id ?? null;
                    $next->save();
                }

                $process->delete();

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
    public function changeStatus(User $user, int $process_id)
    {
        try {
            $process = $this->_repository->find($process_id);

            if ($process && $process->project->company->id == $user->company->id) {
                $process->update([
                    'end_time' => $process->end_time ? null : now(),
                ]);

                return $this->findById($user, $process_id);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
