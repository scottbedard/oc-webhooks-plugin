<?php namespace Bedard\Webhooks\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Bedard\Webhooks\Models\Hook;
use System\Classes\SettingsManager;

/**
 * Hooks Back-end Controller
 */
class Hooks extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Owl.Behaviors.ListDelete.Behavior',
        'Backend.Behaviors.RelationController',
    ];

    public $bodyClass = 'compact-container';
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        $this->useAssets();
        BackendMenu::setContext('October.System', 'system', 'users');
        SettingsManager::setContext('Bedard.Webhooks', 'webhooks');
    }

    /**
     * Use the plugin assets
     *
     * @return void
     */
    protected function useAssets()
    {
        $this->addJs('/plugins/bedard/webhooks/assets/js/script.js');
        $this->addCss('/plugins/bedard/webhooks/assets/css/style.css');
    }

    /**
     * Join a subquery counting the logs.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function listExtendQuery($query)
    {
        $query->joinLogsCount();
    }

    public function onEnable()
    {
        Hook::whereIn('id', post('checked'))->enable();
    }

    public function onDisable()
    {
        Hook::whereIn('id', post('checked'))->disable();
    }
}
