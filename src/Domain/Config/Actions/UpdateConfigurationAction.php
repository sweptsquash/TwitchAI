<?php

namespace Domain\Config\Actions;

use App\Web\StreamSuggestions\Requests\UpdateConfigurationRequest;
use Domain\Config\Models\Config;

class UpdateConfigurationAction
{
    public function execute(UpdateConfigurationRequest $request): void
    {
        Config::updateOrCreate(['key' => 'twitch_name'], ['value' => $request->input('twitchUsername')]);
        Config::updateOrCreate(['key' => 'open_ai_key'], ['value' => $request->input('openAiKey')]);
        Config::updateOrCreate(['key' => 'open_ai_org'], ['value' => $request->input('openAiOrg')]);
    }
}
