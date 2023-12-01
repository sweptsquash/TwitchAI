<?php

namespace App\Web\StreamSuggestions\Controllers;

use Domain\StreamSuggestion\Jobs\ProcessStreamSuggestionJob;
use Domain\StreamSuggestion\Models\StreamSuggestion;
use GuzzleHttp\Exception\ConnectException;
use JsonException;
use OpenAI\Exceptions\TransporterException;
use OpenAI\Exceptions\UnserializableResponse;
use Support\Controllers\Controller;

class RetryStreamSuggestionController extends Controller
{
    public function __invoke(StreamSuggestion $streamSuggestion)
    {
        $suggestion = $streamSuggestion;

        try {
            ProcessStreamSuggestionJob::dispatchSync($streamSuggestion);
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            return ['model' => $streamSuggestion, 'error' => $e->getMessage()];
        } catch (TransporterException|ConnectException|JsonException|UnserializableResponse $e) {
            return ['model' => $streamSuggestion, 'error' => $e->getMessage()];
        }

        return is_array($suggestion) ? to_route('show', $suggestion['model'])->error($suggestion['error']) : to_route('show', $suggestion);
    }
}
