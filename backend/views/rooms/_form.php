<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rooms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gorko_id')->textInput() ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'restaurant_id')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'capacity_reception')->textInput() ?>

    <?= $form->field($model, 'capacity')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'type_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'rent_only')->textInput() ?>

    <?= $form->field($model, 'banquet_price')->textInput() ?>

    <?= $form->field($model, 'bright_room')->textInput() ?>

    <?= $form->field($model, 'separate_entrance')->textInput() ?>

    <?= $form->field($model, 'features')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cover_url')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
