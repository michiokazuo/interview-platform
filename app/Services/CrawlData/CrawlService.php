<?php

namespace App\Services\CrawlData;

use App\Models\CV;
use App\Models\User;
use phpDocumentor\Reflection\Types\Nullable;

interface CrawlService
{
    /**
     * @param int $count
     * @param bool $rollback
     * @param string|null $tag
     * @param bool $tag_rollback
     * @return mixed
     */
    public function crawl(int $count = 100, bool $rollback = false, string $tag = null, bool $tag_rollback = false);
}
