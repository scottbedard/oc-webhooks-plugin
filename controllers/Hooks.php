<?php namespace Bedard\Webhooks\Controllers;

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
}
