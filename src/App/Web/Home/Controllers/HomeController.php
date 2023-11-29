<?php

namespace App\Web\Home\Controllers;

use App\Web\Home\Requests\StoreStreamSuggestionRequest;
use App\Web\Home\Requests\UpdateConfigurationRequest;
use App\Web\Home\Resources\StreamSuggestionResource;
use Domain\Config\Actions\UpdateConfigurationAction;
use Domain\Config\Models\Config;
use Domain\StreamSuggestion\Actions\StoreStreamSuggestionAction;
use Domain\StreamSuggestion\Models\StreamSuggestion;
use Support\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $streamSuggestions = StreamSuggestion::orderByDesc('created_at')->get();

        return inertia('Index', [
            'intitialConfiguration' => Config::where('key', 'channel_name')->whereNull('value')->exists(),
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
