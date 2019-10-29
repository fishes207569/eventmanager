<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace ccheng\eventmanager\common\libs;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;

class BaseAction extends \yii\rest\Action
{
    public static function getParams()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!key_exists('key', $params) || !key_exists('from_system', $params) || !key_exists('data', $params) || (!key_exists('type', $params) || $params['type'] != 'EventNotify')) {
            throw new InvalidParamException('request parameter error');
        }
        $action_params                      = $params['data'];
        $action_params['event_from_system'] = strtolower($params['from_system']);

        return $action_params;
    }

}
