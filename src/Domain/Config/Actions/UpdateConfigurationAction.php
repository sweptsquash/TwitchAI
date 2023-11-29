<?php

namespace Domain\Config\Actions;

use App\Web\Home\Requests\UpdateConfigurationRequest;
use Domain\Config\Models\Config;

class UpdateConfigurationAction
{
    public function execute(UpdateConfigurationRequest $request): void
    {
        Config::updateOrCreate(['key' => 'channel_name'], ['value' => $request->input('channelName')]);
        Config::updateOrCreate(['key' => 'openai_key'], ['value' => $request->input('openAiKey')]);
    }
}
