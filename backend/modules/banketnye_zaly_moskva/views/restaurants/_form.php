<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $rest_model backend\rest_models\Restaurants */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurants-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($rest_model, 'id')->textInput(['disabled' => true]) ?>

    <?= $form->field($rest_model, 'gorko_id')->textInput(['disabled' => true]) ?>

    <?= $form->field($rest_model, 'name')->textInput(['disabled' => true]) ?>

    <?= $form->field($rest_model, 'address')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'value')->textarea(['rows' => 6])->label('SEO текст') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
