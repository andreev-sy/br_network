<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        
        <?= $form->field($model, 'id')->textInput(['maxlength' => true, 'disabled'=>true]) ?>

        <?= $form->field($model, 'language')->textInput(['maxlength' => true, 'disabled'=>true]) ?>

        <hr>
        <h4><?= Yii::t('app', 'Текст:') .' '.$model->message ?></h4>

        <?= $form->field($model, 'translation')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
