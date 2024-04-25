<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Cities;
use backend\models\District;
use backend\models\Region;
use backend\models\User;
use backend\models\VenuesSpec;
use backend\models\ViaHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\VenuesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="venues-search" data-filter style="display: none">
 
    <?php $form = ActiveForm::begin([
        'action' => Url::current(),
        'method' => 'get',
        'options' => [
            // 'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'id') ?></div>
        <div class="col-md-4"><?= $form->field($model, 'name') ?></div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
                'data' => Cities::getMap(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите город'), 
                    'value' => $model->city_id,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'region_id')->widget(Select2::classname(), [
                'data' => Region::getMap(), 
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите округ'), 
                    'value' => $model->region_id,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'district_id')->widget(Select2::classname(), [
                'data' => District::getMap(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите район'), 
                    'value' => $model->district_id,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
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
            <?= $form->field($model, 'manager_user_id')->widget(Select2::classname(), [
                'data' => User::getMap(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите менеджера'),
                    'value' => $model->manager_user_id,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'vendor_user_id')->widget(Select2::classname(), [
                'data' => User::getMap(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите продавца'),
                    'value' => $model->vendor_user_id,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'param_pool')->dropDownList([
                '' => '',
                '0' => Yii::t('app', 'Нет'),
                '1' => Yii::t('app', 'Да'),
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'param_open_area')->dropDownList([
                '' => '',
                '0' => Yii::t('app', 'Нет'),
                '1' => Yii::t('app', 'Да'),
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'param_place_barbecue')->dropDownList([
                '' => '',
                '0' => Yii::t('app', 'Нет'),
                '1' => Yii::t('app', 'Да'),
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'is_processed')->dropDownList([
                '' => '',
                '0' => Yii::t('app', 'Нет'),
                '1' => Yii::t('app', 'Да'),
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'is_contract_signed')->dropDownList([
                '' => '',
                '0' => Yii::t('app', 'Нет'),
                '1' => Yii::t('app', 'Да'),
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'is_phoned')->dropDownList([
                '' => '',
                '0' => Yii::t('app', 'Нет'),
                '1' => Yii::t('app', 'Да'),
            ]) ?>
        </div>
    </div>


    <?php // echo $form->field($model, 'status_id') ?>
    <?php // echo $form->field($model, 'city_id') ?>
    <?php // echo $form->field($model, 'site_id') ?>
    <?php // echo $form->field($model, 'address') ?>
    <?php // echo $form->field($model, 'price_day_ranges') ?>
    <?php // echo $form->field($model, 'work_time') ?>
    <?php // echo $form->field($model, 'phone') ?>
    <?php // echo $form->field($model, 'phone2') ?>
    <?php // echo $form->field($model, 'phone_wa') ?>
    <?php // echo $form->field($model, 'param_spec') ?>
    <?php // echo $form->field($model, 'description') ?>
    <?php // echo $form->field($model, 'comment') ?>
    <?php // echo $form->field($model, 'param_type') ?>
    <?php // echo $form->field($model, 'param_location') ?>
    <?php // echo $form->field($model, 'param_kitchen') ?>
    <?php // echo $form->field($model, 'param_kitchen_type') ?>
    <?php // echo $form->field($model, 'param_cuisine') ?>
    <?php // echo $form->field($model, 'param_advanced_payment') ?>
    <?php // echo $form->field($model, 'param_firework') ?>
    <?php // echo $form->field($model, 'param_firecrackers') ?>
    <?php // echo $form->field($model, 'param_parking_dedicated') ?>
    <?php // echo $form->field($model, 'param_parking') ?>
    <?php // echo $form->field($model, 'param_outdoor_capacity') ?>
    <?php // echo $form->field($model, 'param_alcohol') ?>
    <?php // echo $form->field($model, 'param_own_alcohol') ?>
    <?php // echo $form->field($model, 'param_decor_policy') ?>
    <?php // echo $form->field($model, 'param_dj') ?>
    <?php // echo $form->field($model, 'param_extra_services') ?>
    <?php // echo $form->field($model, 'param_bridal_suite') ?>
    <?php // echo $form->field($model, 'param_payment') ?>
    <?php // echo $form->field($model, 'param_can_order_food') ?>
    <?php // echo $form->field($model, 'param_own_menu') ?>
    <?php // echo $form->field($model, 'param_specials') ?>
    <?php // echo $form->field($model, 'param_seating_arrangement') ?>
    <?php // echo $form->field($model, 'param_parking_type') ?>
    <?php // echo $form->field($model, 'param_video') ?>
    <?php // echo $form->field($model, 'latitude') ?>
    <?php // echo $form->field($model, 'longitude') ?>
    <?php // echo $form->field($model, 'google_id') ?>
    <?php // echo $form->field($model, 'google_place_id') ?>
    <?php // echo $form->field($model, 'google_about') ?>
    <?php // echo $form->field($model, 'google_description') ?>
    <?php // echo $form->field($model, 'google_rating') ?>
    <?php // echo $form->field($model, 'google_reviews') ?>
    <?php // echo $form->field($model, 'google_reviews_link') ?>
    <?php // echo $form->field($model, 'google_location_link') ?>
    <?php // echo $form->field($model, 'created_at') ?>
    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Найти'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Сбросить'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
