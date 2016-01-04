<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\image\models\ImageItem */

$this->title = Yii::t('image', 'Update {modelClass}: ', [
    'modelClass' => 'Image Item',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('image', 'Image Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('image', 'Update');
?>
<div class="image-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
