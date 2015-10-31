<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Description of SubscribeAsset
 *
 * @author Trubachev Denis
 */
class SubscribeAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/subscribe.css',
    ];

}
