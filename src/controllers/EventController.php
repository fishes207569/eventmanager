<?php

namespace ccheng\eventmanager\controllers;

use ccheng\eventmanager\helpers\DateHelper;
use Yii;
use ccheng\eventmanager\models\BizEvent;
use ccheng\eventmanager\models\Searchs\EventSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventController implements the CRUD actions for BizEvent model.
 */
class EventController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BizEvent models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHistory()
    {
        $week_days   = DateHelper::getNowWeeks();
        $searchModel = new EventSearch();
        $now_week    = DateHelper::getNowWeeks('', 'Y-m-d');
        $params      = ['EventSearch' => ['event_date' => $now_week]];

        return $this->render('history', [
            'events'    => $searchModel->searchHistory($params),
            'week_days' => $week_days,
            'now_date'  => date('Y-m-d'),
        ]);
    }

    /**
     * Displays a single BizEvent model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = 'mini';

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BizEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new BizEvent();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->event_id]);
        }

        $this->layout = 'mini';
        !$model->event_date && $model->event_date = date('Y-m-d');
        !$model->event_author && $model->event_author = \Yii::$app->user->identity->username;
        !$model->event_from_system && $model->event_from_system = BizEvent::SYSTEM_BIZ;

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing BizEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return Html::tag('script', 'window.parent.close_layer_from()');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BizEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BizEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return BizEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BizEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actions()
    {
        return [
            'upload' => [
                'class'  => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => \Yii::$app->request->baseUrl,//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                    "imageRoot"       => Yii::getAlias("@webroot"),
                ],
            ],
        ];
    }
}
