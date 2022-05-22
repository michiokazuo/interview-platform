<?php

namespace App\Services\Mail;

use Exception;
use Illuminate\Support\Facades\Mail;

class MailServiceImpl implements MailService
{
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function send(array $emails, string $class, array $data)
    {
        try {
            Mail::to($emails)->send(new $class($data));
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}
