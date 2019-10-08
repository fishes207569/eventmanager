<?php

use ccheng\eventmanager\widgets\Timeline;

/** @var $events \ccheng\eventmanager\models\BizEvent */
/** @var $week_days array */
/** @var $now_date array */

$this->title                   = '事件墙';
$this->params['breadcrumbs'][] = $this->title;
$config = call_user_func(\Yii::$app->params['event_manager_config']);
?>

<div class="history-view">
    <div class="box">
        <div class="box-header with-border" style="height:45px">
            <h3 class="box-title">事件历史</h3>
            <div class="box-tools pull-right"><?= \kartik\widgets\DatePicker::widget([
                    'name'          => 'event_start_date',
                    'value'         => \Yii::$app->request->getQueryParam('event_start_date', date('Y-m-d')),
                    'options'       => [
                        'placeholder' => '选择日期',
                    ],
                    'pluginOptions' => [
                        'autoclose'      => true,
                        'format'         => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                    ],
                    'pluginEvents'  => [
                        "changeDate" => "function(e) {  
                            let val=$(e.target).find('input').val();
                            let url='" . \yii\helpers\Url::to(['history']) . "?event_start_date='+val;
                            window.location.href=url;
                         }",
                    ],
                    'removeButton'  => false,

                ]) ?></div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="body-box">
                        <?= Timeline::widget([
                            'events'       => $events,
                            'week_days'    => $week_days,
                            'target'       => $now_date,
                            'event_config' => $config['event_level'],
                        ]); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>