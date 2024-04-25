<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">


        <?= $form->field($model, 'fullname')->textInput() ?>
        <?= $form->field($model, 'username')->textInput() ?>
        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [ 'mask' => '+55 99 99999 9999' ]); ?>
        <?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model, 'password')->textInput() ?>
        <?= $form->field($model, 'status')->dropDownList($model->status_list); ?>
        <?= $form->field($model, 'files')->fileInput(['accept' => 'image/*']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div> 
 