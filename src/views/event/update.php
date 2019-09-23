<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BizEvent */

$this->title = 'Update Biz Event: ' . $model->event_id;
$this->params['breadcrumbs'][] = ['label' => 'Biz Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->event_id, 'url' => ['view', 'id' => $model->event_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="biz-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
