<?php
ini_set("max_execution_time", 120);
ini_set('display_errors',1);
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('APP_PATH', realpath('..'));

try {

	// include APP_PATH.'/app/library/ucenter/ucenter.php';
	//引入后会对验证码有影响
	/**
	 * Read the configuration
	 */
	$config = include __DIR__ . "/../app/config/config.php";


	/**
	 * Read auto-loader
	 */
	include __DIR__ . "/../app/config/loader.php";

	/**
	 * Read services
	 */
	include __DIR__ . "/../app/config/services.php";

	/**
	 * Include composer autoloader
	 */
	// require APP_PATH . "/vendor/autoload.php";

	$application = new \Phalcon\Mvc\Application($di);

	echo $application->handle()->getContent();

} catch (\Exception $e) {
	echo('<!DOCTYPE html>');
	echo '<meta charset="UTF-8">';
	echo '执行文件';
	echo $e->getFile();
	echo '<br/>第';
	echo $e->getLine();
	echo '行时发生错误：<br/>';


	echo $e->getMessage();

}
