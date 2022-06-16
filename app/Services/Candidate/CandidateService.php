<?php

namespace App\Services\Candidate;

interface CandidateService
{
    /**
     * @param array $inputCandidate
     * @return mixed
     */
    public function store(array $inputCandidate);
}
