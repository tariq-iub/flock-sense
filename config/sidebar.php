<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'menu' => [[
      'text' => 'Navigation',
      'is_header' => true
    ],[
        'url' => '/home',
        'icon' => 'fa fa-laptop',
        'text' => 'Dashboard'
    ],[
      'url' => '/users',
      'icon' => 'fa fa-users',
      'text' => 'Users'
    ]
  ]
];
