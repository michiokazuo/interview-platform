<?php

namespace App\Services\Company;

use App\Models\Company;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class CompanyServiceImpl implements CompanyService
{
    protected $_repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(Company::class);
    }

    /**
     * @inheritDoc
     */
    public function store(array $inputCompany)
    {
        try {
            $urls = $this->_repository->where('url', $inputCompany['url'])->get();
            
            if (!empty($inputCompany['id'])) {
                $company = $this->_repository->findOrFail($inputCompany['id']) ?? [];
                if($urls->count() == 1 && $company['id'] == $urls[0]['id']) {
                    $company->update([
                        'url' => $inputCompany['url'],
                    ]);
                    
                    return $this->_repository->find($company->id);
                }
            } else if(!$urls->count()) {
                return $this->_repository->create([
                    'url' => $inputCompany['url'],
                ]);
            }
            
            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
