<?php

namespace App\Services\SettingCrawler;

use App\Jobs\CrawlData;
use App\Models\SettingCrawler;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class SettingCrawlerServiceImpl implements SettingCrawlerService
{
    protected $_repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->_repository = app()->make(SettingCrawler::class);
    }

    /**
     * @inheritDoc
     */
    public function crawler(User $user, array $tags, int $numbers)
    {
        try {
            if ($user->role_id === 1) {
                $run = "running";
                $stop = "stop";

                $status = $stop;
                $status_key = 'status_run_crawler';
                $dataStatus = $this->_repository->where('key', $status_key)->first();

                if ($dataStatus) {
                    $status = $dataStatus->value;
                } else {
                    $this->_repository->create([
                        'key' => $status_key,
                        'value' => $run
                    ]);
                }
                $dataStatus = $this->_repository->where('key', $status_key)->first();

                if ($status == $stop) {
                    $this->_repository->create([
                        'key' => 'crawler',
                        'value' => json_encode([
                            'user_id' => $user->id,
                            'tags' => $tags,
                            'numbers' => $numbers
                        ])
                    ]);
                    CrawlData::dispatch($dataStatus, $tags, $numbers);

                    return true;
                }
            }
            
            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }
}
