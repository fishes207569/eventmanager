<?php
namespace ccheng\eventmanager\widgets;

use ccheng\eventmanager\EventAsset;
use ccheng\eventmanager\helpers\ConfigHelper;
use ccheng\eventmanager\helpers\StringHelper;
use yii\base\Widget;

class Timeline extends Widget
{
    public $week_days;
    public $events;
    public $target;
    public $event_colors;
    public $event_levels;
    public $event_systems;

    public function run()
    {

        $this->event_colors  = ConfigHelper::getEventLevelConfig('color');
        $this->event_levels  = ConfigHelper::getEventLevelConfig('label');
        $this->event_systems = ConfigHelper::getEventSystemConfig();
        $this->registerAssets();

        return $this->renderWidgetContent();
    }

    private function registerAssets()
    {
        $view = $this->getView();
        EventAsset::register($view);
        $js = <<<JS
    $(function () {
        $('[data-magnify]').Magnify({
            Toolbar: [
                'prev',
                'next',
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
        $('div.event_content>span.content_more').hide();
        $('.event_content_show').bind('click',function() {
            $(this).parents('span.content_desc').hide();
            $(this).parents('div.event_content').children('.content_more').show();
        });
        $('.event_content_hide').bind('click',function() {
            $(this).parents('span.content_more').hide();
            $(this).parents('div.event_content').children('.content_desc').show();
           
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
                                                //let url=window.location.pathname+'?event_start_date='+start_date;
                                                if(url.indexOf('event/event')!=-1){
                                                    window.location.reload();
                                                    //window.location.href=url;
                                                }
                                            }
                                        });
            window.top.layer.full(window.top.layer_from_index);
        });

    })
JS;
        $view->registerJs($js, \yii\web\View::POS_END);

    }

    private function renderWidgetContent()
    {

        $widget = <<<HTML
    <div class="timer-shaft-box">
        <div class="timer-shaft">
            <div class="timer-left" title="后一天"></div>
            <div class="timer-right" title="前一天"></div>
            <div class="timer-scale">{$this->getTimelineTitle()}</div>
        </div>
        <div class="timer-shaft-content">{$this->getTimelineEvent()}</div>
    </div>
HTML;

        return $widget;
    }

    private function getTimelineTitle()
    {
        $title_template = <<<HTML
<div class="timer-scale-cont hov">
    <p class="time-circle">%s</p>
    <p class="line-scale"></p>
</div>
HTML;
        $content        = '';
        foreach ($this->week_days as $day) {
            $template = sprintf($title_template, $day);
            if (date('d', strtotime($this->target)) != $day) {
                $template = str_replace('hov', '', $template);
            }
            $content .= $template;
        }

        return $content;
    }

    public function getTimelineEvent()
    {


        $event_html = [];
        foreach ($this->events as $date => $data) {
            $event_template = <<<HTML
			<div class="shaft-detail-cont" @display>
				<p class="timer-year" style="margin: 0px"><i class="icon-year"></i><span>@date</span></p>
                    @event
			</div>
HTML;
            $format_date    = date('Y年m月d日', strtotime($date));
            if ($this->target == $date) {
                $event_template = str_replace('@display', 'style="display: block;"', $event_template);
            } else {
                $event_template = str_replace('@display', '', $event_template);
            }
            $event_html[$date] = str_replace('@date', $format_date, $event_template);
            $event_content     = '';
            foreach ($data as $event) {
                if (array_key_exists($event['event_level'], $this->event_colors)) {
                    $event_style = 'background-color:' . $this->event_colors[$event['event_level']];
                    $event_level = $this->event_levels[$event['event_level']];
                } else {
                    $event_style = '';
                    $event_level = $event['event_level'];
                }
                $tag_is_show=$event['event_tags']?'':'hide';
                $content_template = <<<HTML
        <div class="month-detail-box">
            <span class="month-title">
                <span class="event_level_icon" title="$event_level" style="$event_style"></span>
                @time
            </span>
            <div class="incident-record">
            <div class="event_name" style="font-weight: bold">标题：{$event['event_name']}</div>
            <div class="event_content"><span style="font-weight: bold">内容：</span><br/>@event_content</div>
            <div class="event_from_system"><span style="font-weight: bold">来源：</span>{$this->event_systems[$event['event_from_system']]}</div>
            <div class="event_tags $tag_is_show"><span style="font-weight: bold">标签：</span>@tags</div>
            <div>
                <span style="color:gray">作者：{$event['event_author']}</span>
            </div>
            </div>
        </div>
HTML;
                $edit_is_show     = $event['event_user_id'] == \Yii::$app->user->getId() ? '' : 'hide';
                if (mb_strlen(strip_tags($event['event_content'])) > 128) {
                    $desc = StringHelper::cut_str(strip_tags($event['event_content']), 128, '...<br/><a href="javascript:void(0)" class="event_content_show pull-right glyphicon glyphicon-eye-open" style="margin-left: 30px" title="查看更多"></a>');
                } else {
                    if (substr_count($event['event_content'], '<img')) {
                        $desc = strip_tags($event['event_content']) . '...<br/><a href="javascript:void(0)" class="glyphicon glyphicon-eye-open event_content_show pull-right" style="margin-left: 30px" title="查看更多"></a>';
                    } else {
                        $desc = strip_tags($event['event_content']);
                    }
                }
                $edit_action = '&nbsp;&nbsp;<a href="javascript:void(0)" data-id="' . $event['event_id'] . '" data-date="'.$event['event_date'].'" class="glyphicon glyphicon-pencil event_update pull-right ' . $edit_is_show . '"  title="编辑"></a>';
                $desc        .= $edit_action;

                $event['event_content'] = <<<CONTENT
                <span class="content_desc">$desc</span><span class="content_more">{$event['event_content']}<a href="javascript:void(0)" class="event_content_hide pull-right glyphicon glyphicon-eye-close" style="margin-left: 30px" title="隐藏详情"></a>$edit_action</span>
CONTENT;

                if ($event['event_image']) {
                    $event['event_content'] = '<a href="javascript:void(0)" data-magnify="gallery" data-group="g1" data-src="' . $event['event_image'] . '" data-caption="' . $event['event_name'] . '">
            <img class="content_img" src="' . $event['event_image'] . '">
        </a>' . $event['event_content'];
                }

                $content_template = (str_replace('@tags', $this->buildTags($event), $content_template));
                $content_template = (str_replace('@time', date('H:i', strtotime($event['event_date'] . ' ' . $event['event_time'])), $content_template));
                $event_content    .= (str_replace('@event_content', $event['event_content'], $content_template));
            }
            if (!$event_content) {
                $event_content = <<<EMPTY
<div class="month-detail-box">
    <span class="month-title"></span>
    <p class="incident-record">世界静悄悄···</p>
</div>
EMPTY;
            }
            $event_html[$date] = str_replace('@event', $event_content, $event_html[$date]);
        }

        return implode('', $event_html);
    }

    private function buildTags($event)
    {
        $tagHtml = '';
        $color   = ConfigHelper::getTagColor($event['event_level']);
        $tags    = explode(',', $event['event_tags']);
        foreach ($tags as $tag) {
            $tagHtml .= "<span class='label' style='background-color: {$color};margin:0 2px'>$tag</span>";
        }

        return $tagHtml;
    }
}