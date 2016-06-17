<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('common', 'login');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options'=>[
        'class'=>'form-signin'
    ]
]); ?>
<h2 class="form-signin-heading"><?= Html::encode($this->title) ?></h2>
<div class="login-wrap">

    <?= $form->field($model, 'username',[
        'inputOptions' => ['class'=>'form-control', 'placeholder' => Yii::t('common', 'username')],
        'inputTemplate' => '<div class="input-group m-bot15">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                {input}
                            </div>',
    ])->label(false) ?>
    <?= $form->field($model, 'password',[
        'inputOptions' => ['class'=>'form-control', 'placeholder' => Yii::t('common', 'password')],
        'inputTemplate' => '<div class="input-group m-bot15">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                {input}
                            </div>',
    ])->passwordInput()->label(false) ?>

    <?= $form->field($model, 'rememberMe',[
        'inputTemplate'=>'{input}',
        'options' => [
        ],
    ])->checkbox() ?>


    <?= Html::submitButton(Yii::t('common', 'login'), ['class' => 'btn btn-lg btn-login btn-block', 'name' => 'login-button']) ?>
</div>
<?php ActiveForm::end(); ?>

