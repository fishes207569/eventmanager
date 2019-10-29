<?php
namespace ccheng\eventmanager\api;

use Yii;
use yii\base\Model;
use yii\httpclient\Request;
use yii\rest\ActiveController;
use yii\web\Response;

class ApiBaseController extends ActiveController
{
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, [$this, 'errorHandler']);

        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    protected function serializeData($data)
    {
        $response = [
            "code"    => 0,
            "message" => "ok",
            "data"    => [],
        ];
        if ($data instanceof Model && $data->hasErrors()) {
            $response["code"]    = 1;
            $response["message"] = 'error';
        }
        $data             = Yii::createObject($this->serializer)->serialize($data);
        $response["data"] = $data;

        return $response;
    }

    public function errorHandler($event)
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            $response = $event->sender;

            $responseData   = [
                "code"    => 1,
                "message" => isset($response->data['message']) ? $response->data['message'] : 'ok',
                "data"    => [],
            ];
            $response->data = $responseData;
        }
        Yii::$app->getResponse()->setStatusCode(200);
    }
}