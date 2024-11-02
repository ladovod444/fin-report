<?php

namespace User\FinReport\Controller\Api;

use User\FinReport\Model\FinReports;

class ApiController
{
    public function getUserData() {
        $finReports = new FinReports('../settings.ini');

        //return $finReports->getData();
    }


}