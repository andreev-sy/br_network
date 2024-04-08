<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Images */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="images-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'venue_id')->textInput() ?>

        <?= $form->field($model, 'room_id')->textInput() ?>

        <?= $form->field($model, 'realpath')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'subpath')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'webppath')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'waterpath')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'timestamp')->textInput() ?>

        <?= $form->field($model, 'sort')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
