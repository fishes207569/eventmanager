<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use ccheng\eventmanager\models\BizEvent;
use kartik\datetime\DateTimePicker;

\ccheng\eventmanager\AdminLteAsset::register($this);

/* @var $this yii\web\View */
/* @var $model ccheng\eventmanager\models\BizEvent */
/* @var $form yii\widgets\ActiveForm */
/* @var $event_systems array */
/* @var $event_levels array */
/* @var $event_colors array */
?>
<style>
    .magic-radio {
        position: absolute;
        display: none;
    }

    .magic-radio + label {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        vertical-align: middle;
    }

    .magic-radio + label:hover:before {
        animation-duration: 0.4s;
        animation-fill-mode: both;
        animation-name: hover-color;
    }

    .magic-radio + label:before {
        position: absolute;
        top: 0;
        left: 0;
        display: inline-block;
        width: 20px;
        height: 20px;
        content: '';
        border-width: 2px;
        border-style: solid;
        border-radius: 50%;
    }

    <?php
     foreach ($event_colors as $name=>$color){
         echo <<<STYLE
.magic-radio + label.radio{$name}:before {
        border-color: {$color}; }\n
STYLE;
     }
     ?>
    .magic-radio + label:after {
        position: absolute;
        display: none;
        content: '';
        top: 4px;
        left: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .magic-radio[disabled] + label {
        cursor: not-allowed;
        color: #e4e4e4;
    }

    .magic-radio:checked + label:before {
        animation-name: none;
    }

    .magic-radio:checked + label:after {
        display: block;
    }

    <?php
     foreach ($event_colors as $name=>$color){
         echo <<<STYLE
.magic-radio + label.radio{$name}:after {
        background: {$color}; }\n
STYLE;
     }
     ?>
</style>
<div class="biz-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'event_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_content')->widget('kucha\ueditor\UEditor', [
        'clientOptions' => [
            //编辑区域大小
            'initialFrameHeight' => '200',
            //设置语言
            'lang'               => 'zh-cn', //中文为 zh-cn
            //定制菜单
            'toolbars'           => [
                [
                    'fullscreen',
                    'source',
                    'undo',
                    'redo',
                    '|',
                    'fontsize',
                    'bold',
                    'italic',
                    'underline',
                    'fontborder',
                    'strikethrough',
                    'removeformat',
                    'formatmatch',
                    'autotypeset',
                    'blockquote',
                    'pasteplain',
                    '|',
                    'forecolor',
                    'backcolor',
                    '|',
                    'lineheight',
                    '|',
                    'indent',
                    '|',
                ],
            ],
        ],
    ]) ?>

    <?= $form->field($model, 'event_date')->widget(DateTimePicker::classname(), [
        'options'       => ['placeholder' => '事件发生时间'],
        'pluginOptions' => [
            'autoclose' => true,
            'todayBtn' => true
        ],
    ]) ?>

    <?= $form->field($model, 'event_level', [
        'template' => '{label}{input}{hint}{error}',
    ])->radioList($event_levels,
        [
            'item' => function ($index, $label, $name, $checked, $value) {
                $checked = $checked ? "checked" : "";
                $return  = '<input type="radio" id="' . $name . $value . '" type="radio" name="' . $name . '" value="' . $value . '" class="magic-radio"  ' . $checked . '>';
                $return  .= '<label for="' . $name . $value . '" class="radio' . $value . '">' . $label . '</label>';

                return $return;
            },
        ]) ?>

    <?= $form->field($model, 'event_from_system')->widget(Select2::class, [
        'data'       => $event_systems,
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