<?php

namespace App\Services\CVDetail;

use App\Models\CV;
use App\Models\CVDetail;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class CVDetailServiceImpl implements CVDetailService
{
    protected $_repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(CVDetail::class);
    }

    /**
     * @inheritDoc
     */
    public function store(User $user, int $cvId, array $data)
    {
        try {
            $candidate_id = $user['candidate_id'];

            if ($cvId) {
                $this->delete($user, $cvId);
            }

            $dataDetail = [];
            $time = now();
            foreach ($data as $item) {
                $dataDetail[] = [
                    'key' => $item['key'],
                    'value' => $item['value'],
                    'type' => $item['type'],
                    'candidate_id' => $candidate_id,
                    'cv_id' => $cvId,
                    'created_at' => $time,
                    'updated_at' => $time
                ];
            }
            $cvDetail = $this->_repository->insert($dataDetail);

            if ($cvDetail) {
                return $cvDetail;
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
    public function delete(User $user, int $cvId)
    {
        try {
            if ($cvId) {
                return $this->_repository->where('cv_id', $cvId)->delete();
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
