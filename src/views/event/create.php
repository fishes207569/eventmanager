<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \ccheng\eventmanager\models\BizEvent */
/* @var $event_systems array */
/* @var $event_levels array */
/* @var $event_colors array */

$this->title                   = 'Create Biz Event';
$this->params['breadcrumbs'][] = ['label' => 'Biz Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biz-event-create">
    <div class="ibox ibox-content" style="padding:0px 15px;">

    <?= $this->render('_form', [
        'model' => $model,
        'event_systems'=>$event_systems,
        'event_levels'=>$event_levels,
        'event_colors'=>$event_colors,
    ]) ?>

    </div>
</div>
