<?php

date_default_timezone_set('Europe/Paris');
ini_set('memory_limit','512M');

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once __DIR__.'/../vendor/autoload.php';

$saparDirectoryApp = new \Cpyree\SaparDirectoryApp();

$saparDirectoryApp->after(function (\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\HttpFoundation\Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});
$saparDirectoryApp->run();

