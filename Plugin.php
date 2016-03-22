<?php namespace Bedard\Webhooks;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

/**
 * Webhook Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'bedard.webhooks::lang.plugin.name',
            'description' => 'bedard.webhooks::lang.plugin.description',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-code',
        ];
    }

    /**
     * Returns plugin settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'webhooks' => [
                'label'       => 'bedard.webhooks::lang.plugin.name',
                'description' => 'bedard.webhooks::lang.plugin.description',
                'category'    => SettingsManager::CATEGORY_SYSTEM,
                'icon'        => 'icon-code',
                'url'         => Backend::url('bedard/webhooks/hooks'),
            ],
        ];
    }
}
