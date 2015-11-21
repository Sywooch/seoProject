<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use \yii\bootstrap\Tabs;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
<?php
if (Yii::$app->getSession()->hasFlash('error')) {
    echo '<div class="alert alert-danger">' . Yii::$app->getSession()->getFlash('error') . '</div>';
}
echo Tabs::widget([
    'items' => [
        [
            'label' => 'Login Form',
            'content' => $this->render('_login_form', compact('model')),
            'active' => true
        ],
        [
            'label' => 'Social login',
            'content' => $this->render('_login_auth'),
        ],
    ],
]);
?>
</div>
