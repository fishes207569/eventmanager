<?php
/**
 * Created by PhpStorm.
 * User: dongxinyun
 * Date: 2018/12/19
 * Time: 下午3:36
 */

namespace ccheng\eventmanager;


use yii\web\AssetBundle;

class EventAsset extends AssetBundle
{

    public $sourcePath = '@ccheng/eventmanager/assets';
    public $baseUrl = '@web';

    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/sjz.js'
    ];


    public $depends = [
        'yii\web\JqueryAsset',
        'ccheng\eventmanager\ViewerAsset'
    ];
}