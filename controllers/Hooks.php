<?php namespace Bedard\Webhooks\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Hooks Back-end Controller
 */
class Hooks extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $bodyClass = 'compact-container';
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
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
     * Index
     *
     * @param  integer|null     $userId
     * @return void
     */
    public function index($userId = null)
    {
        $this->useAssets();
        $this->asExtension('ListController')->index();
    }

    /**
     * Join a subquery counting the logs.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function listExtendQuery($query)
    {
        $query->joinLogsCount()->select('bedard_webhooks_hooks.*', 'logs.logs_count');
    }
}
