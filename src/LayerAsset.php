<?php
namespace ccheng\eventmanager;

use yii\web\AssetBundle;

class LayerAsset extends AssetBundle
{
    public $sourcePath = '@bower/layer/dist';
    public $css        = [
        'theme/default/layer.css',
    ];
    public $js         = [
        'layer.js',
    ];

}
