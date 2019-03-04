<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Management';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to Signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-management']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                
                <?= $form->field($model, 'confirmPassword')->passwordInput() ?>
                
                <?= $form->field($model, 'email') ?>
                
                <?= $form->field($model, 'telNumber')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Apply', ['class' => 'btn btn-primary', 'name' => 'management-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>