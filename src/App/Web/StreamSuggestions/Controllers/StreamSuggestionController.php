<?php

namespace App\Web\StreamSuggestions\Controllers;

use App\Web\StreamSuggestions\Requests\StoreStreamSuggestionRequest;
use App\Web\StreamSuggestions\Requests\UpdateConfigurationRequest;
use App\Web\StreamSuggestions\Resources\StreamSuggestionResource;
use Domain\Config\Actions\UpdateConfigurationAction;
use Domain\Config\Models\Config;
use Domain\StreamSuggestion\Actions\StoreStreamSuggestionAction;
use Domain\StreamSuggestion\Models\StreamSuggestion;
use Support\Controllers\Controller;

class StreamSuggestionController extends Controller
{
    public function index()
    {
        $configKeys = collect(['twitch_name', 'open_ai_key', 'open_ai_org']);

        $initialSetup = Config::whereIn('key', $configKeys->toArray())->doesntExist();

        if ($initialSetup) {
            $configKeys->each(fn ($key) => Config::create(['key' => $key]));
        }

        $streamSuggestions = StreamSuggestion::orderByDesc('created_at')->get();

        return inertia('Index', [
            'intitialConfiguration' => $initialSetup || Config::where('key', 'open_ai_key')->whereNull('value')->exists(),
            'streamSuggestions' => StreamSuggestionResource::collection($streamSuggestions),
        ]);
    }

    public function store(StoreStreamSuggestionRequest $request, StoreStreamSuggestionAction $storeStreamSuggestionAction)
    {
        $suggestion = $storeStreamSuggestionAction->execute($request);

        return is_array($suggestion) ? to_route('show', $suggestion['model'])->error($suggestion['error']) : to_route('show', $suggestion);
    }

    public function show(StreamSuggestion $streamSuggestion)
    {
        return inertia('View', [
            'streamSuggestion' => StreamSuggestionResource::make($streamSuggestion),
        ]);
    }

    public function update(UpdateConfigurationRequest $request, UpdateConfigurationAction $updateConfigurationAction)
    {
        $updateConfigurationAction->execute($request);

        return back()->success('Configuration updated.');
    }
}
