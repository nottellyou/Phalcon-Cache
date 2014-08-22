<?php

    use Phalcon\DI\FactoryDefault, Phalcon\Mvc\View, Phalcon\Mvc\Dispatcher, Phalcon\Mvc\Url as UrlResolver, Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter, Phalcon\Mvc\View\Engine\Volt as VoltEngine, Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter, Phalcon\Session\Adapter\Files as SessionAdapter;


    $di = new FactoryDefault();

    $di->set('router', function () {
        return require __DIR__ . '/routes.php';
    }, true);


    $di->set('url', function () use ($config) {
        $url = new UrlResolver();
        $url->setBaseUri($config->application->baseUri);
        return $url;
    }, true);


    $di->set('view', function () use ($config) {

        $view = new View();

        $view->setViewsDir($config->application->viewsDir);

        $view->registerEngines(array('.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array('compiledPath' => $config->application->cacheDir, 'compiledSeparator' => '_'));

            return $volt;
        }, '.phtml' => 'Phalcon\Mvc\View\Engine\Php'));

        return $view;
    }, true);


    $di->set('db', function () use ($config) {
        return new DbAdapter(
            array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                "options" => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                )
            )
        );
    });


    $di->set('modelsMetadata', function () use ($config) {
        return new MetaDataAdapter();
    });


    $di->set('session', function () {
        $session = new SessionAdapter();
        $session->start();
        return $session;
    });



    $di->set('cache', function () {
        return new Cache();  // extend cache library volt
    });

    $di->set('config', $config);

    $di->set('dispatcher', function () use ($di) {

            $evManager = $di->getShared('eventsManager');

            $evManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) {
                    switch ($exception->getCode()) {
                        case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(
                                array(
                                    'namespace' => 'Pixarty\Controllers\Frontend',
                                    'controller' => 'index',
                                    'action' => 'index',
                                )
                            );
                            return false;
                            break; // for checkstyle
                            default:
                                $dispatcher->forward(
                                    array(
                                        'controller' => 'errors',
                                        'action' => 'uncaughtException',
                                    )
                                );
                                return false;
                                break; // for checkstyle
                    }
                });
            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($evManager);
            $dispatcher->setDefaultNamespace('Pixarty\Controllers\Frontend');
            return $dispatcher;
        }, true);

