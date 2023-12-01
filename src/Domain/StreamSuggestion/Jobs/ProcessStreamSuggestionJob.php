<?php

namespace Domain\StreamSuggestion\Jobs;

use Domain\Config\Models\Config;
use Domain\StreamSuggestion\Models\StreamSuggestion;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use OpenAI as OAI;
use OpenAI\Client as OpenAiClient;
use OpenAI\Responses\Chat\CreateResponse as ChatCreateResponse;

class ProcessStreamSuggestionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private OpenAiClient $client;

    public function __construct(
        private StreamSuggestion $streamSuggestion,
        public int $timeout = 120
    ) {
    }

    public function handle()
    {
        $this->client = OAI::factory()
            ->withApiKey(Config::firstWhere('key', 'open_ai_key')->value)
            ->withOrganization(Config::firstWhere('key', 'open_ai_org')->value)
            ->withHttpClient(new GuzzleClient(['timeout' => $this->timeout]))
            ->make();

        $gptResponse = $this->getResponseFromtOpenAi();

        if (is_null($gptResponse)) {
            return;
        }

        $choice = Arr::first($gptResponse->choices);

        $data = $choice?->message?->content;

        if (is_null($data)) {
            return;
        }

        $data = json_decode($data, true);

        $this->streamSuggestion->update(['response' => $data]);
    }

    private function getResponseFromtOpenAi(): ChatCreateResponse
    {
        return $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => view('templates.open-ai.prompts.stream-suggestion', [
                        'channelName' => Config::firstWhere('key', 'twitch_name')->value,
                        'activity' => $this->streamSuggestion->activity,
                        'category' => $this->streamSuggestion->category,
                        'language' => $this->streamSuggestion->language,
                    ])->render(),
                ],
            ],
        ]);
    }
}
