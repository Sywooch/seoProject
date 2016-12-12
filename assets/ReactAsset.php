<?php
namespace app\assets;


use yii\web\AssetBundle;

class ReactAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/tic-tac.css'
    ];

    public $js = [
        '//unpkg.com/babel-standalone@6/babel.min.js',
        ['js/HelloWorld.js', 'type' => 'text/babel']

    ];

    public $depends = [
        'opw\react\ReactAsset'
    ];
}