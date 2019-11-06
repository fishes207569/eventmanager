<?php

use yii\helpers\Html;
use yii\grid\GridView;
use ccheng\eventmanager\helpers\StringHelper;
use ccheng\eventmanager\ViewerAsset;

/* @var $this yii\web\View */
/* @var $searchModel ccheng\eventmanager\models\Searchs\EventSearch */
/* @var $events array */
\ccheng\eventmanager\AdminLteAsset::register($this);
\yii\web\JqueryAsset::register($this);
$this->title                   = '事件日历';
$this->params['breadcrumbs'][] = $this->title;
$config                        = call_user_func(\Yii::$app->params['event_manager_config']);
?>
<?= $this->render('_search', ['model' => $searchModel, 'config' => $config, 'action' => 'list']); ?>
<div class="box box-primary">
    <?= \ccheng\eventmanager\common\Fullcalendar\yii2fullcalendar::widget([
        'events' => $events,
        'header' => [
            'left'   => 'prev,next today',
            'center' => 'title',
            'right'  => 'month,agendaWeek,listWeek',
        ],
        'eventClick'=>new \yii\web\JsExpression("function(event){
            if(typeof window.top.makeTab === 'function'){
                window.top.makeTab(event.url);
                return false;
            }
        }")
    ]); ?>
</div>