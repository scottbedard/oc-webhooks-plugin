<?php

return [

    //
    // Plugin
    //
    'plugin' => [
        'name' => 'Webhooks',
        'description' => 'Integrate your application with external services.',
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
        'controller' => 'Webhook',
        'copied_to_clipboard' => 'URL successfully copied to keyboard.',
        'disable' => 'Disable',
        'enable' => 'Enable',
        'execute' => 'Execute',
        'execute_running' => 'Executing...',
        'execute_success' => 'Successfully executed webhook.',
        'execute_failed' => 'Failed to execute webhook.',
        'list_title' => 'Manage Webhooks',
        'model' => 'Hook',
        'status_enabled' => 'Enabled',
        'status_enabled_msg' => 'Successfully enabled the selected webhooks.',
        'status_disabled' => 'Disabled',
        'status_disabled_msg' => 'Successfully enabled the selected webhooks.',
        'columns' => [
            'name' => 'Name',
            'executed_at' => 'Last executed',
            'logs_count' => 'Logs',
            'url' => 'URL',
            'status' => 'Status',
            'execute_btn' => 'Test webhook',
        ],
        'form' => [
            'http_method' => 'HTTP Method',
            'http_method_get' => 'Get',
            'http_method_post' => 'Post',
            'name_label' => 'Name',
            'name_placeholder' => 'Enter a name...',
            'status' => 'Status',
            'token' => 'Token',
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
        'failed' => 'Failed',
        'not_found' => 'Not found',
    ],

    //
    // Permissions
    //
    'permissions' => [
        'tab' => 'Webhooks',
        'manage_hooks' => 'Manage webhooks',
    ],
];
