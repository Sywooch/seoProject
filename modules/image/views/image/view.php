<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\image\models\ImageItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('image', 'Image Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('image', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('image', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('image', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'name',
            'description:ntext',
            'created_at',
            'update_at',
        ],
    ]) ?>

</div>
