<?php
return [
    'settings' => [
        'displayErrorDetails' => true,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
				],
				// Database settings
        // 'db' => [
				// 	'driver' => 'sqlite',
				// 	'database' => __DIR__.'/../../blog.db',
				// 	'prefix' => ''
				// ], 
    ], // closing square bracket for 'settings'
]; // closing squre bracket for bracket after return keyword
