<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rooms-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'gorko_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'restaurant_id') ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'capacity_reception') ?>

    <?php // echo $form->field($model, 'capacity') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'type_name') ?>

    <?php // echo $form->field($model, 'rent_only') ?>

    <?php // echo $form->field($model, 'banquet_price') ?>

    <?php // echo $form->field($model, 'bright_room') ?>

    <?php // echo $form->field($model, 'separate_entrance') ?>

    <?php // echo $form->field($model, 'features') ?>

    <?php // echo $form->field($model, 'cover_url') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
