<?php

use EventManager\models\BizEvent;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model EventManager\models\Searchs\EventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="biz-event-search">

    <?php $form = ActiveForm::begin([
        'action'  => ['index'],
        'method'  => 'get',
        "options" => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'event_from_system')->widget(Select2::class,[
        'data'       => BizEvent::SYSTEM_MAP,
        'name'=>'EventSearch[event_from_system]',
        'theme'      => Select2::THEME_DEFAULT,
        'hideSearch' => true,
        'options'    => [
            'placeholder' => '选择来源系统',
        ],
    ])->label(false); ?>
    <?php echo $form->field($model, 'event_author')->textInput(['maxLength'=>32,'placeholder' => '添加人员'])->label(false) ?>
    <?= $form->field($model, 'event_name', ['inputOptions' => ['placeholder' => '事件名称', 'class' => 'form-control']])
        ->label(false) ?>

    <?= $form->field($model, 'event_content', ['inputOptions' => ['placeholder' => '事件关键字', 'class' => 'form-control']])
        ->label(false) ?>

    <div class="form-group" style="width: 300px;">
        <?php
        echo (DatePicker::widget([
            'name'          => 'EventSearch[start_date]',
            'value'         => $model->start_date,
            'options'       => [
                'id'          => 'start_date',
                'placeholder' => '起始时间',
            ],
            'type'          => DatePicker::TYPE_RANGE,
            'name2'         => 'EventSearch[end_date]',
            'value2'        => $model->end_date,
            'options2'      => [
                'id'          => 'end_date',
                'placeholder' => '截止时间',
            ],
            'language'      => 'zh-CN',
            'separator'     => '-',
            'pluginOptions' => [
                'autoclose'      => true,
                'format'         => 'yyyy-mm-dd',
                'todayHighlight' => true,
            ],
        ]).'<div class="help-block"></div>');
        ?>
    </div>
    <div class="form-group" style="margin-top: -10px">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
