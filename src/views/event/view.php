<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\BizEvent */
ccheng\eventmanager\LayerAsset::register($this);
$this->title = $model->event_id;
$this->params['breadcrumbs'][] = ['label' => '事件管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biz-event-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'event_name',
            [
                'attribute' => '事件内容',
                'format'    => 'raw',
                'value'     => function ($model) {
                    return $model->event_content;
                },
            ],
            'event_year',
            'event_month',
            'event_date',
            'event_create_at',
            'event_update_at',
            'event_from_system',
            'event_author',
        ],
    ]) ?>
    <script>
        window.parent.layer.title('<h4>事件详情</h4>',window.parent.layer_from_index);
    </script>
</div>
