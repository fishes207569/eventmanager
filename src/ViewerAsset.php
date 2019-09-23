<?php
/**
 * Created by PhpStorm.
 * User: dongxinyun
 * Date: 2018/12/19
 * Time: 下午3:36
 */

namespace EventManager;


use yii\web\AssetBundle;

class ViewerAsset extends AssetBundle
{

    public $sourcePath = '@EventManager/assets';
    public $baseUrl = '@web';

    public $css = [
        'css/index.css'
    ];
    public $js = [
        'js/index.js',
        'js/jquery.rotate.min.js',
    ];


    public $depends = [
        'yii\web\JqueryAsset',
    ];
}