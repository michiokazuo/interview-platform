<?php

namespace App\Services\DailyCo;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;

class DailyCoServiceImpl implements DailyCoService
{
    /**
     * @inheritDoc
     */
    public function getAll(User $user)
    {
        try {
            $header = $this->createHeader();
            $response = Http::withHeaders($header['headers'])->get($header['url']);
            return $response->json();
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function create(User $user, array $data = [])
    {
        try {
            $header = $this->createHeader();
            $response = Http::withHeaders($header['headers'])->post($header['url'], $data);
            return $response->json();
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(User $user, $room_name)
    {
        try {
            if ($room_name) {
                $header = $this->createHeader();
                $response = Http::withHeaders($header['headers'])->delete($header['url'] . $room_name);
                return $response->json();
            }
            return false;
        } catch (Exception $e) {
            logger()->error($e);
            return false;
        }
    }

    private function createHeader(): array
    {
        return [
            'url' => env('API_URL_DAILY_CO') . 'rooms/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('API_KEY_DAILY_CO'),
                'Content-Type' => 'application/json'
            ]
        ];
    }
}
