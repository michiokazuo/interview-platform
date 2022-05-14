<?php

namespace App\Services\Candidate;

use App\Models\Candidate;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class CandidateServiceImpl implements CandidateService
{
    protected $_repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(Candidate::class);
    }


    /**
     * @inheritDoc
     */
    public function store(array $inputCandidate)
    {
        try {
            if (!empty($inputCandidate['id'])) {
                $candidate = $this->_repository->findOrFail($inputCandidate['id']);
                if ($candidate) {
                    return $candidate;
                }
            } else {
                return $this->_repository->create([]);
            }

            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
