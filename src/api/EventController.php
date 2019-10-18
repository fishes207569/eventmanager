<?php

namespace ccheng\eventmanager\api;

use Yii;
use ccheng\eventmanager\models\Api\BizEvent;
use yii\web\ForbiddenHttpException;

/**
 * EventController implements the CRUD actions for BizEvent model.
 */
class EventController extends ApiBaseController
{

    public $modelClass=BizEvent::class;


    public function checkAccess($action, $model = null, $params = [])
    {
        if(in_array($action,['create'])){
            if($model && $model->event_user_id){
                throw new ForbiddenHttpException('无权限进行该操作！');
            }
        }else{
            throw new ForbiddenHttpException('尚无该权限！');
        }
    }
}
