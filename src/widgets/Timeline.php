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
    public $event_config;
    public $event_colors;
    public $event_levels;

    public function run()
    {

        $this->event_colors = array_map(function ($item) {
            return $item['color'];
        }, $this->event_config);
        $this->event_levels = array_map(function ($item) {
            return $item['label'];
        }, $this->event_config);
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
        $('div.incident-record .content_more').hide();
        $('.event_content_show').bind('click',function() {
            $(this).parents('span.content_desc').hide();
            $(this).parents('div.incident-record').children('.content_more').show();
        });
        $('.event_content_hide').bind('click',function() {
            $(this).parents('span.content_more').hide();
            $(this).parents('div.incident-record').children('.content_desc').show();
           
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
                $content_template = <<<HTML
        <div class="month-detail-box">
            <span class="month-title"><span class="event_level_icon" title="$event_level" style="$event_style"></span>@time</span>
            <div class="incident-record">@event_content</div>
        </div>
HTML;
                if (mb_strlen(strip_tags($event['event_content'])) > 128) {
                    $desc = StringHelper::cut_str(strip_tags($event['event_content']), 128, '<br/><a href="javascript:void(0)" class="event_content_show pull-right" title="查看更多">查看更多</a>');
                } else {
                    if (substr_count($event['event_content'], '<img')) {
                        $desc = strip_tags($event['event_content']) . '<br/><a href="javascript:void(0)" class="event_content_show pull-right" title="查看更多">查看更多</a>';
                    } else {
                        $desc = strip_tags($event['event_content']);
                    }
                }
                $tags = $this->buildTags($event);

                $event['event_content'] = <<<CONTENT
                <span class="content_desc">$desc</span><span class="content_more">{$event['event_content']}<a href="javascript:void(0)" class="event_content_hide pull-right">隐藏详情</a></span><p class="event_tags">{$tags}</p><br/><span style="color:gray">作者：{$event['event_author']}</span>
CONTENT;

                if ($event['event_image']) {
                    $event['event_content'] = '<a href="javascript:void(0)" data-magnify="gallery" data-group="g1" data-src="' . $event['event_image'] . '" data-caption="' . $event['event_name'] . '">
            <img class="content_img" src="' . $event['event_image'] . '">
        </a>' . $event['event_content'];
                }

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
        $tags=explode(',',$event['event_tags']);
        foreach ($tags as $tag) {
            $tagHtml .= "<span class='label' style='background-color: {$color};margin:0 2px'>$tag</span>";
        }
        return $tagHtml;
    }
}