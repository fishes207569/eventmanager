<?php
namespace ccheng\eventmanager\helpers;

class ConfigHelper
{
    public static function getTagColor($eventLevel)
    {
        $config = call_user_func(\Yii::$app->params['event_manager_config']);
        $levels = $config['event_level'];

        return $levels[$eventLevel]['color'];
    }

    public static function getEventLevelConfig($key)
    {
        $config = call_user_func(\Yii::$app->params['event_manager_config']);

        return array_map(function ($item) use ($key) {
            return $item[$key];
        }, $config['event_level']);
    }

    public static function getEventSystemConfig()
    {
        $config = call_user_func(\Yii::$app->params['event_manager_config']);

        return $config['event_system'];
    }
}