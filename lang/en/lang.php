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
    // Hooks
    //
    'hooks' => [
        'controller' => 'Web Hooks',
        'list_title' => 'Manage Web Hooks',
        'model' => 'Hook',
        'form' => [
            'directory_label' => 'Execute from directory',
            'directory_placeholder' => '~',
            'name_label' => 'Name',
            'name_placeholder' => 'Enter a name...',
        ],
        'tabs' => [
            'script' => 'Script',
        ],
    ],
];
