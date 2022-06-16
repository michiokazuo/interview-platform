<?php

namespace App\Services\SettingCrawler;

use App\Models\User;

interface SettingCrawlerService
{
    /**
     * @param User $user
     * @param array $tags
     * @param int $numbers
     * @return mixed
     */
    public function crawler(User $user, array $tags, int $numbers);

}
