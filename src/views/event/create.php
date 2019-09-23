<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BizEvent */

$this->title                   = 'Create Biz Event';
$this->params['breadcrumbs'][] = ['label' => 'Biz Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biz-event-create">
    <div class="ibox ibox-content" style="padding:0px 15px;"

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    </div>
</div>
