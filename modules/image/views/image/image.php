<?php

use \yii\bootstrap\Html;
use app\modules\image\assets\ImageAsset;

\app\modules\image\assets\AngularAsset::register($this);
ImageAsset::register($this);
$id = $model->id;
if (!$model->isNewRecord) {
    $image = $model->getImage();
    if ($image) {
        $url = $image->getUrl();
    }
}
?>
<script type="text/javascript" >
    'use strict';
    var app = angular.module('app', [
        'ngRoute', //$routeProvider
        'mgcrea.ngStrap' //bs-navbar, data-match-route directives,
    ]);
    app.value('imageModel', {
        <?php if (!$model->isNewRecord):?>
        name: "<?=$model->name?>",
        id: "<?=$model->getPrimaryKey()?>",
        file: "<?=$url?>"
        <?php endif;?>
        
    });
</script>
<div ng-controller="ImageCtrl">
    <div class="preview" id="preview">
        <div class="inner" id="draw" ng-mousedown="addPoint($event)" style="width: 100%">
            <canvas id="myimage_canvas"></canvas>
            <img id ="myimage" usemap="#imgmap<?= $id ?>" class="mapper"  src="{{file}}" style="max-width: 100%" >
        </div>
    </div>
    <map name="imgmap<?= $id ?>">
        <area ng-repeat="area in areas" coords="{{getCoordinats(area)}}" shape="poly" 
              alt="alt" title="{{area.title}}" href="#" id="imgmap_{{$index}}" 
              ng-mouseover="mouseover(area)" ng-mouseleave="mouseleave(area)"
              >
    </map>

    <div class="bar" id="bar">
        <div class="row">
            <input type="file" onchange="angular.element(this).scope().uploadFile(this.files)">    
            <?= Html::activeLabel($model, 'name') ?>:
            <?= Html::activeTextInput($model, 'name', ['ng-model' => 'name']) ?>
            <?= Html::activeHiddenInput($model, 'id', ['ng-model' => 'id']) ?>
            <?=
            Html::button(\Yii::t('image', 'Save image'), [
                'ng-click' => 'saveImage()',
                'class' => 'btn btn-primary'
            ])
            ?>
        </div>
        <div class="row" ng-show="showBar">
            <?=
            Html::button(\Yii::t('image', 'Clear all'), [
                'ng-click' => 'clear()',
                'class' => 'btn btn-primary'
            ])
            ?>
            <?=
            Html::button(\Yii::t('image', 'Add area'), [
                'ng-click' => 'addBtn()',
                'class' => 'btn btn-primary',
                'ng-hide' => 'active'
            ])
            ?>
            <?=
            Html::button(\Yii::t('image', 'Save area'), [
                'ng-click' => 'saveBtn()',
                'class' => 'btn btn-primary',
                'ng-show' => 'active'
            ])
            ?>
            <?=
            Html::textInput('', '', [
                'ng-model' => 'title',
                'placeholder' => \Yii::t('image', 'Title'),
                'ng-show' => 'active'
            ])
            ?>
        </div>

    </div>
    <div class="info" id="info"></div>    
</div>
