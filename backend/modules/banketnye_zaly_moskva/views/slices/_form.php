<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Slices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alias')->textInput() ?>

    <?= $form->field($model, 'h1')->textInput() ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <?= $form->field($model, 'params')->textInput() ?>

    <?= $form->field($model, 'keywords')->textInput() ?>

    <?= $form->field($model, 'img_alt')->textInput() ?>

    <?= $form->field($model, 'text_top')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text_bottom')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
