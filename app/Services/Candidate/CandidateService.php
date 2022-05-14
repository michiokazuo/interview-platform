<?php

namespace App\Services\Candidate;

use App\Models\Candidate;

interface CandidateService
{
    /**
     * @param array $inputCandidate
     * @return mixed
     */
    public function store(array $inputCandidate);
}
