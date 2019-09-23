<?php

use yii\helpers\Html;
use yii\grid\GridView;
use ccheng\eventmanager\helpers\StringHelper;
use ccheng\eventmanager\ViewerAsset;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
\ccheng\eventmanager\AdminLteAsset::register($this);
ViewerAsset::register($this);
$this->title                   = '事件列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biz-event-index" id="iviewer-gallery-2">
    <section class="panel " style="padding: 20px">
        <header>搜索条件
            <i class="fa fa-arrow-circle-down text-danger"></i>
        </header>
        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'emptyText'=>'暂无事件记录',
            'columns'      => [
                'event_id',
                'event_date',
                'event_name',
                [
                    'attribute' => 'event_image',
                    'format'    => 'raw',
                    'value'     => function ($model) {
                        if ($model->event_image) {
                            return '<a href="javascript:void(0)" class="imgg" data-magnify="gallery" data-group="g1" data-src="'.$model->event_image.'" data-caption="'.$model->event_name.'"><img style="width:30px;height:30px" src="'.$model->event_image.'"></a>';

                            //return '<img style="height:30px;width:30px" src="' . $model->event_image . '" />';
                        } else {
                            return '未上传';
                        }

                    },
                ],
                [
                    'attribute'      => 'event_content',
                    'format'         => 'raw',
                    'value'          => function ($model) {
                        return StringHelper::cut_str($model->event_content, 20);
                    },
                    'contentOptions' => function ($model, $key, $index, $column) {
                        return ['title' => $model->event_content, 'alt' => $model->event_content];
                    },
                ],

                'event_from_system',
                'event_author',
                'event_create_at',
                //             'event_update_at',
            ],
        ]); ?>
    </section>
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
            keyboard:true,
            draggable:true,
            movable:true,
            modalSize:[800,600],
            beforeOpen:function (obj,data) {
                console.log('beforeOpen')
            },
            opened:function (obj,data) {
                console.log('opened')
            },
            beforeClose:function (obj,data) {
                console.log('beforeClose')
            },
            closed:function (obj,data) {
                console.log('closed')
            },
            beforeChange:function (obj,data) {
                console.log('beforeChange')
            },
            changed:function (obj,data) {
                console.log('changed')
            }
        });

    })
</script>