<?php

namespace App\Console\Commands;

use App\Services\CrawlData\CrawlService;
use Illuminate\Console\Command;

class CrawlDataStackOverflow extends Command
{
    private $crawlService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:data {--count=} {--rollback=} {--tag=} {--tag-rollback=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl data from stackoverflow';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CrawlService $crawlService)
    {
        parent::__construct();
        $this->crawlService = $crawlService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $count = $this->option('count');
        $count = (is_numeric($count) && $count > 0) ? round($count) : 100;
        $rollback = filter_var($this->option('rollback'), FILTER_VALIDATE_BOOLEAN);
        $tag = $this->option('tag');
        $tagRollback = filter_var($this->option('tag-rollback'), FILTER_VALIDATE_BOOLEAN);

        $this->crawlService->crawl($count, $rollback, $tag, $tagRollback);
    }
}
