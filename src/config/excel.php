<?php

return [

    'database' => config('database.connections.'.config('database.default').'.database'),

    'tables' => [
        'asset_templates' => [
            'model' => Bigmom\Excel\Models\AssetTemplate::class,
            'accessor' => [
                'file_type' => 'type_name',
            ],
            'mutator' => [
                'file_type' => 'file_type_from_name',
            ],
            'ignore' => [
                'id',
            ],
        ],
        'folders' => [
            'model' => Bigmom\Excel\Models\Folder::class,
            'accessor' => [
                'folder_type' => 'type_name',
            ],
            'mutator' => [
                'folder_type' => 'folder_type_from_name',
            ],
            'ignore' => [
                'id',
            ],
        ],
        'assets' => [
            'ignore' => [
                'id',
            ],
        ],
    ],

    'limit-tables' => [
        'should-limit' => true,
        'allowed-tables' => [
            'assets',
        ],
    ],

    'export' => [
        'title' => config('app.name') . '_Tables',
    ],

];
