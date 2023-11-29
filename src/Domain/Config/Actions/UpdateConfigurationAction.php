<?php

namespace Domain\Config\Actions;

use App\Web\Home\Requests\UpdateConfigurationRequest;
use Domain\Config\Models\Config;

class UpdateConfigurationAction
{
    public function execute(UpdateConfigurationRequest $request): void
    {
        Config::firstWhere(['key' => 'channel_name'])->update(['value' => $request->input('channelName')]);

        Config::firstWhere(['key' => 'openai_key'])->update(['value' => $request->input('openAiKey')]);
    }
}
