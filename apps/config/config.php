<?php


    return new \Phalcon\Config(array(
        'database' => array(
            'adapter' => '',
            'host' => '',
            'username' => '',
            'password' => '',
            'dbname' => '',
        ),

        'application' => array(
            'controllersDir' => __DIR__ . '/../../apps/controllers/',
            'modelsDir' => __DIR__ . '/../../apps/models/',
            'viewsDir' => __DIR__ . '/../../apps/views/',
            'pluginsDir' => __DIR__ . '/../../apps/plugins/',
            'libraryDir' => __DIR__ . '/../../apps/library/',
            'cacheDir' => __DIR__ . '/../../var/cache/volt/',
            'baseUri' => '/',
            'defaultTitle' => 'Pixarty',
        ),

        'cache' => array(
            'status'=>true, // cache status 
            'memcache'=>false, // memcache status
        ) 


    ));

