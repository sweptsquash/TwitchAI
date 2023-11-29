<?php

namespace Domain\StreamSuggestion\Actions;

use App\Web\Home\Requests\StoreStreamSuggestionRequest;
use Domain\StreamSuggestion\Jobs\ProcessStreamSuggestionJob;
use Domain\StreamSuggestion\Models\StreamSuggestion;
use GuzzleHttp\Exception\ConnectException;
use JsonException;
use OpenAI\Exceptions\TransporterException;
use OpenAI\Exceptions\UnserializableResponse;

class StoreStreamSuggestionAction
{
    public function execute(StoreStreamSuggestionRequest $request): StreamSuggestion|string
    {
        $suggestion = StreamSuggestion::create($request->only(['activity', 'category', 'language']));

        try {
            ProcessStreamSuggestionJob::dispatchSync($suggestion);
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            return ['model' => $suggestion, 'error' => $e->getMessage()];
        } catch (TransporterException|ConnectException|JsonException|UnserializableResponse $e) {
            return ['model' => $suggestion, 'error' => $e->getMessage()];
        }

        return $suggestion;
    }
}
