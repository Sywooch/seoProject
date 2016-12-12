<?php
namespace app\assets;


use yii\web\AssetBundle;

class AsyncReduxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        '//unpkg.com/babel-standalone@6/babel.min.js',
        ['js/redux.min.js'],
        ['js/react-redux.min.js'],
        ['js/testAsyncRedux.js', 'type' => 'text/babel']
    ];

    public $depends = [
        'opw\react\ReactAsset'
    ];
}