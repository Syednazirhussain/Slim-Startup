<?php

// Slim Settings
$slimConfig = [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false,

        // Renderer settings
        'renderer'            => [
            'blade_template_path' => ROOT.'/app/views', // String or array of multiple paths
            'blade_cache_path'    => ROOT.'/cache', // Mandatory by default, though could probably turn caching off for development
        ],
    ],
];



?>