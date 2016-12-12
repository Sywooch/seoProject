<?php
/**
 * Created by PhpStorm.
 * User: den
 * Date: 08.12.16
 * Time: 20:54
 */

namespace app\assets;


use yii\web\AssetBundle;

class ReduxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        '//unpkg.com/babel-standalone@6/babel.min.js',
        ['js/redux.min.js'],
        ['js/react-redux.min.js'],
        ['js/testRedux.js', 'type' => 'text/babel']
    ];

    public $depends = [
        'opw\react\ReactAsset'
    ];
}