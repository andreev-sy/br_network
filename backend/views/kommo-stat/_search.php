<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\KommoLeadsSearch;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\KommoLeadsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kommo-stat-search">

    <?php $form = ActiveForm::begin([
        'action' => Url::current(),
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'filter_year')->widget(DatePicker::classname(), [
                'name' => 'date',
                'options' => ['id' => 'filter_year'],
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'orientation' => 'bottom center',
                    'class' => 'kommo-stat-year',
                    'autoclose' => true,
                    'format' => 'yyyy',
                    'startView' => 'decade',
                    'minViewMode' => 2
                ]
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'filter_month')->widget(DatePicker::classname(), [
                'name' => 'date',
                'options' => ['id' => 'filter_month'],
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'orientation' => 'bottom center',
                    'class' => 'kommo-stat-month',
                    'autoclose' => true,
                    'format' => 'mm',
                    'startView' => 'year',
                    'minViewMode' => 'months',
                ],
                'pluginEvents' => [
                    "show" => "function(e) {  $('.datepicker-dropdown').find('.datepicker-months').addClass('hide-year'); }",
                    "hide" => "function(e) {  $('.datepicker-dropdown').find('.datepicker-months').removeClass('hide-year'); }",
                ],
            ]) ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Найти'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Сбросить'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
