<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CollectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'spec_id') ?>

    <?php // echo $form->field($model, 'guest_id') ?>

    <?php // echo $form->field($model, 'price_person_id') ?>

    <?php // echo $form->field($model, 'contact_type_id') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'desire') ?>

    <?php // echo $form->field($model, 'form_request_id') ?>

    <?php // echo $form->field($model, 'manager_user_id') ?>

    <?php // echo $form->field($model, 'pool') ?>

    <?php // echo $form->field($model, 'place_barbecue') ?>

    <?php // echo $form->field($model, 'open_area') ?>

    <?php // echo $form->field($model, 'hash') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Найти'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Сбросить'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
