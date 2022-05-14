<?php

namespace App\Services\Company;

use App\Models\Company;

interface CompanyService
{
    /**
     * @param array $inputCompany
     * @return mixed
     */
    public function store(array $inputCompany);
}
