<?php

return [

    //
    // Plugin
    //
    'plugin' => [
        'name' => 'Web Hooks',
        'description' => 'Create web hooks to integrate your application with external services.',
    ],

    //
    // Safe mode
    //
    'safe_mode' => [
        'header' => 'Safe Mode Conflict',
        'content' => 'PHP does not allow the use of shell_exec() while in safe mode. If you wish to use this plugin, safe mode must be disabled.',
    ],

    //
    // Hooks
    //
    'hooks' => [
        'controller' => 'Web Hooks',
        'copied_to_clipboard' => 'Copied webhook to clipboard!',
        'disable' => 'Disable',
        'enable' => 'Enable',
        'list_title' => 'Manage Web Hooks',
        'model' => 'Hook',
        'status_enabled' => 'Enabled',
        'status_disabled' => 'Disabled',
        'columns' => [
            'name' => 'Name',
            'directory' => 'Directory',
            'executed_at' => 'Last executed',
            'logs_count' => 'Logs',
            'url' => 'URL',
            'status' => 'Status',
        ],
        'form' => [
            'directory_label' => 'Directory',
            'directory_placeholder' => 'Execution directory...',
            'http_method' => 'HTTP Method',
            'http_method_get' => 'Get',
            'http_method_post' => 'Post',
            'name_label' => 'Name',
            'name_placeholder' => 'Enter a name...',
        ],
        'tabs' => [
            'logs' => 'Logs',
            'script' => 'Script',
            'settings' => 'Settings',
        ],
    ],

    //
    // Logs
    //
    'logs' => [
        'controller' => 'Logs',
        'model' => 'Log',
        'empty_message' => 'This webhook has never been hit.',
        'form' => [
            'created_at' => 'Date executed',
            'output' => 'Output',
        ],
        'columns' => [
            'id' => 'ID',
            'created_at' => 'Date executed',
        ],
    ],

    //
    // Responses
    //
    'responses' => [
        'success' => 'Success',
        'failed' => 'Failed to execute web hook script.',
        'not_found' => 'Web hook not found.',
    ],
];
