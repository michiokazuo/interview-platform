<?php

namespace App\Services\Mail;

use App\Models\Candidate;
use stdClass;

interface MailService
{
    /**
     * @param array $emails
     * @param string $class
     * @param array $data
     * 
     * @return void
     */
    public function send(array $emails, string $class, array $data);
}
