<?php
namespace app\modules\image\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Description of LoginAssets
 *
 * @author Trubachev Denis
 */
class LoginAssets extends AssetBundle
{
     public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/angular/angularLogin.js',
        'js/angular/controllers.js',
    ];
    public $jsOptions = [
        'position' => View::POS_END,
    ];
    public $depends = [
        'app\modules\image\assets\AngularAsset',
    ];
}
