<?php

use ccheng\eventmanager\models\BizEvent;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ccheng\eventmanager\models\Searchs\EventSearch */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="box box-default">
    <div class="box-header with-border">
        <h1 class="box-title">搜索</h1>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'type'   => ActiveForm::TYPE_INLINE,

    ]); ?>
    <div class="box-body">
        <?= $form->field($model, 'event_from_system', [
            'options' => [
                'class' => 'form-group',
                'style' => 'min-width:150px',
            ],
        ])->widget(Select2::class, [
            'data'          => $config['event_system'],
            'name'          => 'EventSearch[event_from_system]',
            'hideSearch'    => true,
            'theme'         => Select2::THEME_KRAJEE,
            'options'       => [
                'placeholder' => '选择来源系统',
                'data-val'=>$model->event_from_system
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false); ?>
        <?= $form->field($model, 'event_level', [
            'options' => [
                'class' => 'form-group',
                'style' => 'min-width:150px',
            ],
        ])->widget(Select2::class, [
            'data'          => array_map(function ($item) {
                return $item['label'];
            }, $config['event_level']),
            'name'          => 'EventSearch[event_level]',
            'theme'         => Select2::THEME_KRAJEE,
            'hideSearch'    => true,
            'options'       => [
                'placeholder' => '选择事件级别',
                'data-val'=>$model->event_level
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false); ?>

        <?php echo $form->field($model, 'event_author')
            ->textInput(['maxLength' => 32, 'placeholder' => '添加人员'])
            ->label(false) ?>
        <?= $form->field($model, 'event_name', ['inputOptions' => ['placeholder' => '事件名称', 'class' => 'form-control']])
            ->label(false) ?>

        <?= $form->field($model, 'event_content', [
            'inputOptions' => [
                'placeholder' => '事件关键字',
                'class'       => 'form-control',
            ],
        ])
            ->label(false) ?>

        <div class="form-group" style="width: 300px;">
            <?php
            echo(DatePicker::widget([
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
                ]) . '<div class="help-block"></div>');
            ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default','onclick'=>new \yii\web\JsExpression("(function(){
                $('select[name^=EventSearch]').each(function(i){
                    $(this).val($(this).data('val')).trigger('change');
                });
            })()")]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
