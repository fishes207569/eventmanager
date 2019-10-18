<?php

namespace ccheng\eventmanager\api;

use ccheng\eventmanager\common\libs\BaseAction;
use Yii;
use ccheng\eventmanager\models\Api\BizEvent;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

/**
 * EventController implements the CRUD actions for BizEvent model.
 */
class EventController extends ApiBaseController
{

    public $modelClass=BizEvent::class;

    public function verbs()
    {
        $verbs = parent::verbs();

        return array_merge($verbs, ['notifying'=>['POST'],'update' => ['POST'],'delete' => ['GET']]);
    }


    public function actionNotifying(){
        $this->checkAccess('notifying');
        /* @var $model \yii\db\ActiveRecord */
        $model = new BizEvent();
        $model->load(BaseAction::getParams(), '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if(in_array($action,['notifying','view'])){
            if($model && $model->event_user_id){
                throw new ForbiddenHttpException('无权限进行该操作！');
            }
        }else{
            throw new ForbiddenHttpException('尚无该权限！');
        }
    }
}
