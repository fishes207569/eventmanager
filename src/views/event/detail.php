<?php

use ccheng\eventmanager\helpers\ConfigHelper;
use ccheng\eventmanager\helpers\StringHelper;
use ccheng\eventmanager\models\Searchs\EventDaySearch;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/** @var $events EventDaySearch */
/** @var $week_days array */
/** @var $now_date array */

$this->title                   = '事件墙';
$this->params['breadcrumbs'][] = $this->title;
$config                        = call_user_func(\Yii::$app->params['event_manager_config']);
?>
<style>
    .timeline-footer a{
        display: inline-block;
        text-align: center;
    }
    .timeline > li > .item-i{
        width: 20px;
        height: 20px;
        left: 22px;
    }
    .input-group .input-group-addon{
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .form-control{
        border-radius: 4px;
    }
</style>
    <div class="box box-default">
        <div class="box-header with-border">
            <h1 class="box-title">搜索</h1>
        </div>

        <?php $form = ActiveForm::begin([
            'action' => ['detail'],
            'method' => 'get',
            'type'   => ActiveForm::TYPE_INLINE,

        ]); ?>
        <div class="box-body">

            <?= $form->field($model, 'event_system',[
                    'options' => [
                        'class' => 'form-group',
                        'style' => 'min-width: 170px',
                    ],
                ]
            )->widget(Select2::class, [
                'data'          => $config['event_system'],
                'hideSearch'    => true,
                'theme'         => Select2::THEME_KRAJEE,
                'options'       => [
                    'placeholder' => '选择来源系统'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(false); ?>
            <?= $form->field($model, 'event_level',[
                'options' => [
                    'class' => 'form-group',
                    'style' => 'min-width: 170px',
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
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(false); ?>
            <?= $form->field($model, 'event_date', [
                'options' => [
                    'class' => 'form-group',
                    'style' => [
                        'max-width' => '170px',
                    ],
                ],
            ])->widget(\kartik\widgets\DatePicker::class, [
                'options'       => [
                    'placeholder' => '事件所属日',
                ],
                'pluginOptions' => [
                    'autoclose'      => true,
                    'format'         => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                ],
            ]) ?>
            <?= $form->field($model, 'event_tag')->textInput([
                'class' => 'form-group',
                'placeholder' => '事件标签',
                'style' => [
                    'max-width' => '170px',
                ],
            ])->label('事件标签') ?>
            <div class="form-group">
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('返回',['/event/event/list'],['class' => 'btn btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="timeline">
                <li class="time-label">
                  <span class="bg-default" style="margin-left: 8px">
                    时间线
                  </span>
                </li>
                <?php if(empty(current($events))): ?>
                    <li>
                        <i class="fa item-i" style="background-color:gray"></i>

                        <div class="timeline-item" id="id_35" style="display:block">
                            <span class="time"><i class="fa fa-clock-o"></i> now</span>

                            <h3 class="timeline-header">
                                <a href="#">管理员 </a>^-^</h3>
                            <div class="timeline-body">
                                <div class="event_content_more_35 "><p>世界静悄悄<br></p></div>
                            </div>
                            <div class="timeline-footer" style="display:block">
                            </div>
                        </div>
                    </li>
                <?php else: ?>
                     <?php foreach(current($events) as $event): ?>
                    <?php
                        /** @var $event \ccheng\eventmanager\models\BizEvent */
                        $system_map=ConfigHelper::getEventSystemConfig();
                    ?>


                    <li>
                        <i class="fa item-i" style="background-color: <?= ConfigHelper::getEventLevelConfig('color')[$event->event_level]  ?>"></i>

                        <div class="timeline-item" style="display:block">
                            <span class="time"><i class="fa fa-clock-o"></i> <?= $event->event_time ?></span>

                            <h3 class="timeline-header">
                                <a href="#"><?= $event->event_author ?> </a> <?= $event->event_name ?>
                            </h3>
                            <?php $content_is_show=mb_strlen(strip_tags($event->event_content))>128 ?>
                            <div class="timeline-body">
                                <div class="event_content_more_<?= $event->event_id ?> <?= $content_is_show?'hide':'' ?>"><?= $event->event_content ?></div>
                                <div class="event_content_desc_<?= $event->event_id ?>  <?= $content_is_show?'':'hide' ?>"><p><?= StringHelper::cut_str(strip_tags($event['event_content']), 128, '...') ?></p></div>
                                <div style="display:flex;justify-content:space-between">
                                    <div class="event_tag" style="color:gray">标签：<?= $event->event_tags?\ccheng\eventmanager\helpers\HtmlHelper::buildTags($event->toArray()):'-' ?></div>
                                    <div style="color:gray">来源：<?= $system_map[$event->event_from_system] ?></div>
                                </div>
                            </div>
                            <div class="timeline-footer" style="display:block">
                                <a class="btn btn-default timeline_action_more event_action_more_<?= $event->event_id ?> btn-xs pull-right <?= $content_is_show?'':'hide' ?>" data-id="<?= $event->event_id ?>">查看更多</a>
                                <a class="btn btn-default timeline_action_desc event_action_desc_<?= $event->event_id ?> btn-xs pull-right hide" data-id="<?= $event->event_id ?>">隐藏详情</a>
                                <a class="btn btn-primary event_update btn-xs <?= \Yii::$app->user->getId()==$event->event_user_id?'':'hide' ?> " data-id="<?= $event->event_id ?>">编辑</a>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- END timeline item -->
                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>
        </div>
    </div>
<script>
    $(function(){
        $('.timeline_action_more').bind('click',function(){
            let obj=$(this);
            obj.hide();
            let more_el='event_content_more_'+obj.data('id');
            let desc_el='event_content_desc_'+obj.data('id');
            $('.'+more_el).removeClass('hide');
            $('.'+more_el).show();
            $('.'+desc_el).hide();
            let more_action_obj='event_action_more_'+obj.data('id');
            let desc_action_obj='event_action_desc_'+obj.data('id');
            $('.'+desc_action_obj).removeClass('hide');
            $('.'+desc_action_obj).show();
            $('.'+more_action_obj).hide();

        });
        $('.timeline_action_desc').bind('click',function(){
            let obj=$(this);
            obj.hide();
            let more_el='event_content_more_'+obj.data('id')
            let desc_el='event_content_desc_'+obj.data('id')
            $('.'+more_el).hide();
            $('.'+desc_el).removeClass('hide');
            $('.'+desc_el).show();
            let more_action_obj='event_action_more_'+obj.data('id');
            let desc_action_obj='event_action_desc_'+obj.data('id');
            $('.'+more_action_obj).removeClass('hide');
            $('.'+more_action_obj).show();
            $('.'+desc_action_obj).hide();
        });
        $('.event_update').bind('click',function(){
            let id=$(this).data('id');
            let start_date=$(this).data('date');
            window.top.layer_from_index = window.top.layer.open({
                type: 2,
                title: '<h4>更新事件</h4>',
                shadeClose: false,
                scrollbar: false,
                maxmin: true,
                shade: 0.8,
                area: ['1000px', '750px'],
                content: [
                    '/event/event/update?id='+id
                ],end: function () {
                    let url=window.location.href;
                    if(url.indexOf('event/event')!=-1){
                        window.location.reload();
                    }
                }
            });
            window.top.layer.full(window.top.layer_from_index);
        });
    });
</script>