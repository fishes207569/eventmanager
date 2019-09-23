<?php
/**
 * Created by PhpStorm.
 * User: dongxinyun
 * Date: 2018/12/19
 * Time: 下午3:36
 */

namespace EventManager;


use yii\web\AssetBundle;

class EventIconAsset extends AssetBundle
{
    public $sourcePath = '@EventManager/assets';
    public $baseUrl = '@web';

    public $js = [
        'js/icon.js'
    ];
    public $css=[
        'css/icon.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'EventManager\LayerAsset'
    ];
}