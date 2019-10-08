<?php

use yii\helpers\Html;
use yii\grid\GridView;
use ccheng\eventmanager\helpers\StringHelper;
use ccheng\eventmanager\ViewerAsset;

/* @var $this yii\web\View */
/* @var $searchModel ccheng\eventmanager\models\Searchs\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
\ccheng\eventmanager\AdminLteAsset::register($this);
\ccheng\eventmanager\LayerAsset::register($this);
ViewerAsset::register($this);
$this->title                   = '事件列表';
$this->params['breadcrumbs'][] = $this->title;
$config                        = call_user_func(\Yii::$app->params['event_manager_config']);
?>
        <?= $this->render('_search', ['model' => $searchModel, 'config' => $config]); ?>
<div class="box box-primary">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'emptyText'    => '暂无事件记录',
            'columns'      => [
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{view}',
                    'header'         => '操作',
                    'buttons'        => [
                        'update' => function ($url, $item, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'javascript:void(0);', [
                                'data-url' => $url,
                                'onclick'  => new \yii\web\JsExpression("(function (thi) {
                                    let url=$(thi).data('url');
                                    window.top.layer_from_index = window.top.layer.open({
                                        type: 2,
                                        title: '<h4>更新事件</h4>',
                                        shadeClose: false,
                                        scrollbar: false,
                                        maxmin: true,
                                        shade: 0.8,
                                        area: ['1000px', '750px'],
                                        content: [
                                            url
                                        ],end: function () {
                                                let url=window.location.href;
                                                if(url.indexOf('event/event')!=-1){
                                                    window.location.reload();
                                                }
                                            }
                                        });
                                    })(this)"),
                            ]);
                        },
                        'view'=> function ($url, $item, $key) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'javascript:void(0);', [
                                'data-url' => $url,
                                'onclick'  => new \yii\web\JsExpression("(function (thi) {
                                    let url=$(thi).data('url');
                                    window.top.layer_from_index = window.top.layer.open({
                                        type: 2,
                                        title: '<h4>事件详情</h4>',
                                        shadeClose: false,
                                        scrollbar: false,
                                        maxmin: true,
                                        shade: 0.8,
                                        area: ['1000px', '750px'],
                                        content: [
                                            url
                                        ]
                                        });
                                    })(this)"),
                            ]);
                        },
                    ],
                    'visibleButtons' => [
                        'update' => function ($model) {
                            if (\Yii::$app->user->identity) {
                                return $model->event_user_id == \Yii::$app->user->identity->getId();
                            } else {
                                return false;
                            }
                        },
                        'delete' => function ($model) {
                            if (\Yii::$app->user->identity) {
                                return $model->event_user_id == \Yii::$app->user->identity->getId();
                            } else {
                                return false;
                            }
                        },
                    ],
                ],

                'event_id',
                'event_date',
                'event_time',
                'event_name',
                [
                    'attribute'      => 'event_content',
                    'format'         => 'raw',
                    'value'          => function ($model) {
                        return StringHelper::cut_str(strip_tags($model->event_content), 30);
                    },
                    'contentOptions' => function ($model, $key, $index, $column) {
                        return [
                            'title' => strip_tags($model->event_content),
                            'alt'   => strip_tags($model->event_content),
                        ];
                    },
                ],
                [
                    'attribute' => 'event_level',
                    'format'    => 'raw',
                    'value'     => function ($model) use ($config) {

                        if (isset($config['event_level']) && !empty($config['event_level'])) {
                            return array_map(function ($item){
                                return $item['label'];
                            },$config['event_level'])[$model->event_level];
                        } else {
                            return $model->event_level;
                        }

                    },
                ],
                [
                    'attribute' => 'event_from_system',
                    'format'    => 'raw',
                    'value'     => function ($model) use ($config) {
                        if (isset($config['event_system']) && !empty($config['event_system'])) {
                            return $config['event_system'][$model->event_from_system];
                        } else {
                            return $model->event_from_system;
                        }
                    },
                ],
                'event_from_system',
                'event_author',
                'event_create_at',
            ],
        ]); ?>
</div>
<script>
    $(function () {
        $('.imgg').Magnify({
            Toolbar: [
                'rotateLeft',
                'rotateRight',
                'zoomIn',
                'actualSize',
                'zoomOut'
            ],
            keyboard: true,
            draggable: true,
            movable: true,
            modalSize: [800, 600],
            beforeOpen: function (obj, data) {
                console.log('beforeOpen')
            },
            opened: function (obj, data) {
                console.log('opened')
            },
            beforeClose: function (obj, data) {
                console.log('beforeClose')
            },
            closed: function (obj, data) {
                console.log('closed')
            },
            beforeChange: function (obj, data) {
                console.log('beforeChange')
            },
            changed: function (obj, data) {
                console.log('changed')
            }
        });

    })
</script>