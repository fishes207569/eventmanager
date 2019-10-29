<?php

namespace ccheng\eventmanager\common\Fullcalendar;

use yii\web\View;
use yii2fullcalendar\CoreAsset;
use yii2fullcalendar\SchedulerAsset;
use yii2fullcalendar\ThemeAsset;

class yii2fullcalendar extends \yii2fullcalendar\yii2fullcalendar
{

    /**
    * Registers the FullCalendar javascript assets and builds the requiered js  for the widget and the related events
    */
    protected function registerPlugin()
    {
        $id = $this->options['id'];
        $view = $this->getView();

        /** @var \yii\web\AssetBundle $assetClass */
        $assets = CoreAsset::register($view);

        //by default we load the jui theme, but if you like you can set the theme to false and nothing gets loaded....
        if($this->theme == true)
        {
            ThemeAsset::register($view);
        }
	
	if (array_key_exists('defaultView',$this->clientOptions) && ($this->clientOptions['defaultView'] == 'timelineDay' || $this->clientOptions['defaultView'] == 'agendaDay'))
        {
            SchedulerAsset::register($view);
        }    

        if (isset($this->options['lang']))
        {
            $assets->language = $this->options['lang'];
        }

        if ($this->googleCalendar)
        {
            $assets->googleCalendar = $this->googleCalendar;
        }

        $js = array();

        if($this->ajaxEvents != NULL){
            $this->clientOptions['events'] = $this->ajaxEvents;
        }
	    
	if(!is_null($this->contentHeight) && !isset($this->clientOptions['contentHeight']))
        {
            $this->clientOptions['contentHeight'] = $this->contentHeight;
        }

        if(is_array($this->header) && isset($this->clientOptions['header']))
        {
            $this->clientOptions['header'] = array_merge($this->header,$this->clientOptions['header']);
        } else {
            $this->clientOptions['header'] = $this->header;
        }

		if(isset($this->defaultView) && !isset($this->clientOptions['defaultView']))
        {
            $this->clientOptions['defaultView'] = $this->defaultView;
        }

        // clear existing calendar display before rendering new fullcalendar instance
        // this step is important when using the fullcalendar widget with pjax
        $js[] = "var loading_container = jQuery('#$id .fc-loading');"; // take backup of loading container
        $js[] = "jQuery('#$id').empty().append(loading_container);"; // remove/empty the calendar container and append loading container bakup

        $cleanOptions = $this->getClientOptions();
        $js[]=<<<JS
        $.fullCalendar.datepickerLocale('zh-cn', 'zh-CN', {
  closeText: "关闭",
  prevText: "&#x3C;上月",
  nextText: "下月&#x3E;",
  currentText: "今天",
  monthNames: [ "一月","二月","三月","四月","五月","六月",
  "七月","八月","九月","十月","十一月","十二月" ],
  monthNamesShort: [ "一月","二月","三月","四月","五月","六月",
  "七月","八月","九月","十月","十一月","十二月" ],
  dayNames: [ "星期日","星期一","星期二","星期三","星期四","星期五","星期六" ],
  dayNamesShort: [ "周日","周一","周二","周三","周四","周五","周六" ],
  dayNamesMin: [ "日","一","二","三","四","五","六" ],
  weekHeader: "周",
  dateFormat: "yy-mm-dd",
  firstDay: 1,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: "年" });


$.fullCalendar.locale("zh-cn", {
  buttonText: {
    month: "月",
    week: "周",
    day: "日",
    list: "日程"
  },
  allDayText: "全天",
  eventLimitText: function(n) {
    return "另外 " + n + " 个";
  },
  noEventsMessage: "没有事件显示"
});
JS;
        $js[] = "jQuery('#$id').fullCalendar($cleanOptions);";

        /**
        * Loads events separately from the calendar creation. Uncomment if you need this functionality.
        *
        * lets check if we have an event for the calendar...
            * if(is_array($this->events))
            * {
            *    foreach($this->events AS $event)
            *    {
            *        $jsonEvent = Json::encode($event);
            *        $isSticky = $this->stickyEvents;
            *        $js[] = "jQuery('#$id').fullCalendar('renderEvent',$jsonEvent,$isSticky);";
            *    }
            * }
        */

        $view->registerJs(implode("\n", $js),View::POS_READY);
    }

}
