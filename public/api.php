<?php

include_once "../vendor/autoload.php";

use User\FinReport\Controller\Api\ApiController;
use User\FinReport\Model\FinReports;

if (!empty($_GET['userId'])) {
    $finReports = new FinReports();
    $userBalance = $finReports->getUserMonthBalance($_GET['userId']);

    echo json_encode($userBalance);
}
