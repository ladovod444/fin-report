<?php

include_once "../vendor/autoload.php";

use User\FinReport\Controller\FrontController;
use User\FinReport\Model\FinReports;

// Создаем FrontController инициализаруем БД
$controller = new FrontController();
$finReports = new FinReports();

// Вывод страницы
echo $controller->setModel($finReports)->index();

