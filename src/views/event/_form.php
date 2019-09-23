<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use EventManager\models\BizEvent;

\EventManager\AdminLteAsset::register($this);
/* @var $this yii\web\View */
/* @var $model EventManager\models\BizEvent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="biz-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'event_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_content')->textarea(['rows' => 6,'style'=>"resize:none;"]) ?>

    <?= $form->field($model, 'event_date')->widget(DatePicker::className(), [
        'removeButton'  => false,
        'pickerButton'  => [
            'icon' => 'ok',
        ],
        'pluginOptions' => [
            'autoclose'      => true,
            'format'         => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'clearBtn'       => true,
        ],
        'language'      => 'zh-CN',
    ]) ?>

    <?= $form->field($model, 'event_image')->fileInput(['name'=>'event_image'])->label('事件图像'); ?>
    <?= $form->field($model, 'event_from_system')->widget(Select2::class, [
        'data'       => BizEvent::SYSTEM_MAP,
        'theme'      => Select2::THEME_DEFAULT,
        'hideSearch' => true,
        'options'    => [
            'placeholder' => '选择来源系统',
        ],
    ]) ?>

    <?= $form->field($model, 'event_author')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
