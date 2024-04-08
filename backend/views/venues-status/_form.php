<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesStatus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="venues-status-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'text_ru')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
