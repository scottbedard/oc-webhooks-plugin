<?php namespace Bedard\Webhooks\Controllers;

use Lang;
use Flash;
use Backend;
use Exception;
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
        $this->addJs('/plugins/bedard/webhooks/assets/compiled/webhooks.min.js');
        $this->addCss('/plugins/bedard/webhooks/assets/compiled/webhooks.min.css');
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

    public function onExecute()
    {
        try {
            Hook::find(post('id'))->execute();
            Flash::success(Lang::get('bedard.webhooks::lang.hooks.execute_success'));
        } catch (Exception $e) {
            Flash::error(Lang::get('bedard.webhooks::lang.hooks.execute_failed'));
        }

        return $this->listRefresh();
    }

    public function onEnable()
    {
        Hook::whereIn('id', post('checked'))->enable();
        Flash::success(Lang::get('bedard.webhooks::lang.hooks.status_enabled_msg'));
        return $this->listRefresh();
    }

    public function onDisable()
    {
        Hook::whereIn('id', post('checked'))->disable();
        Flash::success(Lang::get('bedard.webhooks::lang.hooks.status_disabled_msg'));
        return $this->listRefresh();
    }
}
