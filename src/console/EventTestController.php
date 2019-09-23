<?php
namespace ccheng\eventmanager\console;

use yii\console\Controller;

class EventTestController extends Controller
{
    public function actionTest()
    {
        echo 'event hello!';
    }
}