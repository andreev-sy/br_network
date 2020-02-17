<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WidgetMain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="widget-main-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'slice_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'subtitle')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'link_text')->textInput() ?>

    <?= $form->field($model, 'img_alt')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
