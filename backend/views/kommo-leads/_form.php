<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KommoLeads */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kommo-leads-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'lead_id')->textInput() ?>

        <?= $form->field($model, 'labor_cost')->textInput() ?>

        <?= $form->field($model, 'response_time')->textInput() ?>

        <?= $form->field($model, 'response_time_id')->textInput() ?>

        <?= $form->field($model, 'is_night')->textInput() ?>

        <?= $form->field($model, 'status_id')->textInput() ?>

        <?= $form->field($model, 'rejection_id')->textInput() ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
