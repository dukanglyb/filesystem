<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Dispatcher;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
}, true);


$di->set('dispatcher', function() use ($di,$config) {

  $eventsManager = $di->getShared('eventsManager');



  /**
   * Check if the user is allowed to access certain action using the SecurityPlugin
   */
  $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);


  $dispatcher = new Phalcon\Mvc\Dispatcher();

  //-----------------------WARNING！----------------------------
  //-------------------------注意！-----------------------------
  //高级调试模式时，会允许访问所有链接【如果不是非常明白，严禁打开此模式，严禁注释此代码！】
  if(!$config->application->SeniorDebug){
     $dispatcher->setEventsManager($eventsManager);
  }
  return $dispatcher;
});

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->registerDir->default->viewsDir);

    $view->registerEngines(array(

        '.html' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

$di->set('router', function() use ($config){
    $router = new \Phalcon\Mvc\Router\Annotations(true);
    $router->addResource('Bfs');
    return $router;
//    return require APP_PATH . '/app/config/routes.php';
});


$di->set('elements', function(){
    return new Elements();
});

/**
 * write the logger
 */
$di->setShared('logger',function(){
    return   new Phalcon\Logger\Adapter\File(APP_PATH."/app/logs/debug.log");
});
$di->setShared('dispatcherLogger',function(){
    return   new Phalcon\Logger\Adapter\File(APP_PATH."/app/logs/Dispatcher.log");
});

$di->set('email',function(){
    return  new Email();
});

$di->set('uploadFile',function(){
    return new UploadFile();
});

/**
 * Register the flash service with the Twitter Bootstrap classes
 */
$di->set(
    'flash',
    function () {
        return new Phalcon\Flash\Direct(array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning'=>'alert alert-warning',
            'message'=>'alert alert-message',
        ));
    }
);

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set(
    'flashSession',
    function () {
        return new Phalcon\Flash\Session(array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning'=>'alert alert-warning',
            'message'=>'alert alert-message',
        ));
    }
);

$di->set('config', $config);


