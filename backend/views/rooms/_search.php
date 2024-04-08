<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Venues;
use backend\models\VenuesSpec;
use backend\models\ViaHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rooms-search" data-filter style="display: none">

    <?php $form = ActiveForm::begin([
        'action' => Url::current(),
        'method' => 'get',
        'options' => [
            // 'data-pjax' => 1,
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'id') ?></div>
        <div class="col-md-4">
            <?= $form->field($model, 'venue_id')->widget(Select2::classname(), [
                'data' => Venues::getMap(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите заведение'), 
                    'value' => $model->venue_id,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'param_name_alt') ?></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'param_spec_search')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(VenuesSpec::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите мероприятие'),
                    'value' => $model->param_spec_search,
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'price_search[field]')->dropDownList([
                'price_day' => $model->getAttributeLabel('price_day'),
                'price_person' => $model->getAttributeLabel('price_person'),
                'price_hour' => $model->getAttributeLabel('price_hour'),
            ])->label(Yii::t('app', 'Стоимость аренды')) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'price_search[type]')->dropDownList([
                'range' => Yii::t('app', 'Диапазон (100-1000)'),
                'more_than' => Yii::t('app', 'Больше чем'),
                'less_than' => Yii::t('app', 'Меньше чем'),
            ])->label(Yii::t('app', 'Тип')) ?>
        </div>
        <div class="col-md-2"><?= $form->field($model, 'price_search[value]')->label(Yii::t('app', 'Значение')) ?></div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'capacity_search[type]')->dropDownList([
                'exactly' => Yii::t('app', 'Точно'),
                'range' => Yii::t('app', 'Диапазон (100-1000)'),
                'more_than' => Yii::t('app', 'Больше чем'),
                'less_than' => Yii::t('app', 'Меньше чем'),
            ])->label(Yii::t('app', 'Вместимость')) ?>
        </div>
        <div class="col-md-2"><?= $form->field($model, 'capacity_search[value]')->label(Yii::t('app', 'Значение')) ?></div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'is_loft')->dropDownList([
                '' => '',
                '0' => Yii::t('app', 'Нет'),
                '1' => Yii::t('app', 'Да'),
            ]) ?>
        </div>
    </div>

    <?//= $form->field($model, 'param_min_price') ?>
    <?//= $form->field($model, 'param_minimum_rental_duration') ?>
    <?//= $form->field($model, 'price_day_ranges') ?>
    <?//= $form->field($model, 'param_spec') ?>
    <?//= $form->field($model, 'param_payment_model') ?>
    <?//= $form->field($model, 'param_rent_only') ?>
    <?//= $form->field($model, 'param_bright_room') ?>
    <?//= $form->field($model, 'param_separate_entrance') ?>
    <?//= $form->field($model, 'param_air_conditioner') ?>
    <?//= $form->field($model, 'param_area') ?>
    <?//= $form->field($model, 'param_ceiling_height') ?>
    <?//= $form->field($model, 'param_floor') ?>
    <?//= $form->field($model, 'param_total_floors') ?>
    <?//= $form->field($model, 'param_location') ?>
    <?//= $form->field($model, 'param_features') ?>
    <?//= $form->field($model, 'param_description') ?>
    <?//= $form->field($model, 'param_zones') ?>
    <?//= $form->field($model, 'is_loft')->checkbox() ?>
    <?//= $form->field($model, 'loft_food_catering') ?>
    <?//= $form->field($model, 'loft_food_catering_order') ?>
    <?//= $form->field($model, 'loft_food_order') ?>
    <?//= $form->field($model, 'loft_food_can_cook') ?>
    <?//= $form->field($model, 'loft_alcohol_allow') ?>
    <?//= $form->field($model, 'loft_alcohol_order') ?>
    <?//= $form->field($model, 'loft_alcohol_self') ?>
    <?//= $form->field($model, 'loft_alcohol_fee') ?>
    <?//= $form->field($model, 'loft_entrance') ?>
    <?//= $form->field($model, 'loft_style') ?>
    <?//= $form->field($model, 'loft_color') ?>
    <?//= $form->field($model, 'loft_light') ?>
    <?//= $form->field($model, 'loft_interior') ?>
    <?//= $form->field($model, 'loft_equipment_furniture') ?>
    <?//= $form->field($model, 'loft_equipment_interior') ?>
    <?//= $form->field($model, 'loft_equipment1') ?>
    <?//= $form->field($model, 'loft_equipment2') ?>
    <?//= $form->field($model, 'loft_equipment_games') ?>
    <?//= $form->field($model, 'loft_equipment_3') ?>
    <?//= $form->field($model, 'loft_staff') ?>
    <?//= $form->field($model, 'created_at') ?>
    <?//= $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Найти'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Сбросить'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
