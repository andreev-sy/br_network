<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SubdomenPagesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subdomen-pages-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'keywords') ?>

    <?= $form->field($model, 'img_alt') ?>

    <?php // echo $form->field($model, 'h1') ?>

    <?php // echo $form->field($model, 'text_top') ?>

    <?php // echo $form->field($model, 'text_bottom') ?>

    <?php // echo $form->field($model, 'title_pag') ?>

    <?php // echo $form->field($model, 'description_pag') ?>

    <?php // echo $form->field($model, 'keywords_pag') ?>

    <?php // echo $form->field($model, 'h1_pag') ?>

    <?php // echo $form->field($model, 'page_id') ?>

    <?php // echo $form->field($model, 'subdomen_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
