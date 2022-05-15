<?php

namespace App\Services\CV;

use App\Models\CV;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class CVServiceImpl implements CVService
{
    protected $_repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(CV::class);
    }

    
}
