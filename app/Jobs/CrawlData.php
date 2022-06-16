<?php

namespace App\Jobs;

use App\Models\SettingCrawler;
use App\Services\CrawlData\CrawlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;
    private $setting, $tags, $numbers;

    /**
     * Create a new job instance.
     * @param SettingCrawler $setting
     * @param array $tags
     * @param int $numbers
     *
     * @return void
     */
    public function __construct(SettingCrawler $setting, array $tags, int $numbers)
    {
        $this->setting = $setting;
        $this->tags = $tags;
        $this->numbers = $numbers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CrawlService $crawlService)
    {
        $run = "running";
        $stop = "stop";

        $this->setting->update(['value' => $run]);
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                $crawlService->crawl($this->numbers, false, $tag);
            }
        } else {
            $crawlService->crawl($this->numbers);
        }

        logger()->info('done crawl data');
        $this->setting->update(['value' => $stop]);
    }
}
