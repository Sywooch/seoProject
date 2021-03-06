<?php

namespace app\modules\image\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Description of AngularAsset
 *
 * @author Trubachev Denis
 */
class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $js = [
        'angular/angular.js',
        'angular-route/angular-route.js',
        'angular-strap/dist/angular-strap.js',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
}
