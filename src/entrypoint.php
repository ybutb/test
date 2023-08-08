<?php

declare(strict_types=1);

use App\Controller\DataController;
use App\Repository\Repository;
use App\Service\Sender\EmailSender;
use App\Service\Sender\SmsSender;
use App\Service\SessionService;

spl_autoload_register(static function ($class) {
    $file = str_replace('App', 'src', $class);
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file).'.php';

    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
});

$appRoutes = [
    '/' => 'index',
    '/store' => 'store',
];

$currentRoute = $_SERVER['REQUEST_URI'];

if (empty($appRoutes[$currentRoute])) {
    http_redirect('/');
}

$user = $_SERVER['MYSQL_ROOT'] ?? 'test';
$password = $_SERVER['MYSQL_PASSWORD'] ?? 'test';

$dsn = 'mysql:host=db;dbname=test';

$repository = new Repository(new PDO($dsn, $user, $password));

$controller = new DataController(
    $repository,
    new SessionService($repository),
    new SmsSender(new StdClass()),
    new EmailSender(new StdClass())
);

$controller->{$appRoutes[$currentRoute]}();



