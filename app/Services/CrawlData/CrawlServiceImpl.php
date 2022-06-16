<?php

namespace App\Services\CrawlData;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTag;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

class CrawlServiceImpl implements CrawlService
{
    protected $questionRepo, $answerRepo, $tagRepo;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->questionRepo = app()->make(Question::class);
        $this->answerRepo = app()->make(QuestionAnswer::class);
        $this->tagRepo = app()->make(QuestionTag::class);
    }

    /**
     * @inheritDoc
     */
    public function crawl(int $count = 100, bool $rollback = false, string $tag = null, bool $tag_rollback = false)
    {
        try {
            $header = $this->createHeader($count, $rollback, $tag, $tag_rollback);
            $count = $header['count'];

            do {
                $header['params']['page'] = $header['params']['page'] + 1;
                $response = Http::get(env('API_STACK_EXCHANGE'), $header['params']);
                $data = $response->json()['items'] ?? [];

                if (count($data) > 0) {
                    $count -= count($data);

                    if ($tag) {
                        $this->tagRepo->where('name', $tag)->update(['page_crawled' => $header['params']['page']]);
                    }

                    foreach ($data as $item) {
                        $tags_id = [];
                        foreach ($item['tags'] as $tagName) {
                            $tagDB = $this->tagRepo->where('name', $tagName)->first();
                            if (!$tagDB) {
                                $tagDB = $this->tagRepo->create(['name' => $tagName]);
                            }
                            $tags_id[] = $tagDB->id;
                        }

                        $question = [
                            'id' => $item['question_id'],
                            'title' => $item['title'],
                            'others' => ([
                                'is_answered' => $item['is_answered'],
                                'score' => $item['score'],
                            ]),
                            'page_crawled' => $header['params']['page']
                        ];

                        $this->saveQuestion($item['link'], $question, $tags_id);
                    }
                } else {
                    break;
                }
            } while ($count > 0);

            if ($tag && $count === $header['count']) {
                echo "Tag: {$tag} not found. Please fill the tag name correctly. \n";
            } else {
                echo "Crawl data successfully. \n";
            }
        } catch (Exception $e) {
            logger()->error($e);
            echo "Something error. Please try again!!! \n";
        }
    }

    private function createHeader($count, $rollback, $tag, $tag_rollback): array
    {
        $header = [
            'count' => $count,
            'params' => [
                'sort' => 'votes',
                'order' => 'desc',
                'site' => 'stackoverflow',
                'page' => 0,
                'key' => env('API_STACK_EXCHANGE_KEY'),
            ],
        ];

        if ($tag) {
            $tagDB = $this->tagRepo->where('name', $tag)->first();

            if ($tagDB) {
                $header['params']['page'] = $tagDB->page_crawled ?? 0;
            }

            if ($tag_rollback) {
                $header['params']['page'] = 0;
                $header['count'] = DB::table('question_tags')->count();
            }

            $header['params']['tagged'] = $tag;
        } else {
            $header['params']['page'] = $this->questionRepo->max('page_crawled');
            
            if ($rollback) {
                $header['params']['page'] = 0;
                $header['count'] = $this->questionRepo->count();
            }
        }

        return $header;
    }

    private function saveQuestion($url, $question, $tags_id)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $crawler->filter('#mainbar #question')->each(
            function (Crawler $node) use ($tags_id, &$question) {
                $content = $node->filter('.post-layout .postcell.post-layout--right .s-prose.js-post-body');
                $question['content'] = $content->html();

                $questionDB = $this->questionRepo->find($question['id']);
                if (!$questionDB) {
                    $questionDB = $this->questionRepo->create($question);
                } else {
                    $questionDB->update($question);
                }
                
                $questionDB->tags()->sync($tags_id);
            }
        );

        $crawler->filter('#mainbar #answers [data-answerid]')->slice(0, 5)->each(
            function (Crawler $node) use ($question) {
                $answer = [
                    'id' => $node->attr('data-answerid'),
                    'question_id' => $question['id'],
                    'content' => $node->filter('.post-layout .answercell.post-layout--right .s-prose.js-post-body')->html(),
                    'score' => $node->filter('.post-layout .votecell.post-layout--left .js-voting-container .js-vote-count')->attr('data-value') ?? 0,
                    'answered' => !str_contains($node->filter('.post-layout .votecell.post-layout--left .js-voting-container .js-accepted-answer-indicator')->attr('class'), 'd-none')
                ];

                $answerDB = $this->answerRepo->find($answer['id']);
                if (!$answerDB) {
                    $this->answerRepo->create($answer);
                } else {
                    $answerDB->update($answer);
                }
            }
        );
    }
}
