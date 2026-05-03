<?php

return [

    'default' => env('FILESYSTEM_DISK', 'uploads'),

    'disks' => [

        'uploads' => [
            'driver'     => 'local',
            'root'       => public_path('uploads'),
            'url'        => env('APP_URL').'/uploads',
            'visibility' => 'public',
        ],

    ],

];