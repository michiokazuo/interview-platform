<?php

namespace App\Services\CV;

use App\Models\CV;
use App\Models\User;
use App\Services\CVDetail\CVDetailService;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class CVServiceImpl implements CVService
{
    protected $_repository, $cvDetailService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(CVDetailService $cvDetailService)
    {
        $this->_repository = app()->make(CV::class);
        $this->cvDetailService = $cvDetailService;
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, array $data)
    {
        try {
            $cv = $user->candidate->cv;

            if ($cv) {
                $cv[0]->update([
                    'link' => $data['link'],
                ]);
                $cv = $cv[0];
            }
            else {
                $cv = $this->_repository->create([
                    'candidate_id' => $user->candidate_id,
                    'link' => $data['link'],
                ]);
            }
            
            $cvId = $cv->id;
            
            $cvSave = $this->cvDetailService->store($user, $cvId, $data['detail']);

            if ($cvSave) {
                return $cv;
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
    public function findById(User $user, $id)
    {
        try {
            $cv = $user->candidate->cv;
            
            if ($cv) {
                return $cv[0];
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
