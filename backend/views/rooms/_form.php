<?php

use backend\models\RoomsPaymentModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ViaHelper;
use kartik\select2\Select2;
use backend\models\VenuesSpec;
use backend\models\RoomsLocation;
use backend\models\RoomsFeatures;
use backend\models\RoomsZones;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rooms-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body" >

        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'venue_id')->textInput() ?></div>
        </div>

        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'param_min_price')->textInput() ?></div>
            <div class="col-md-3"><?= $form->field($model, 'param_minimum_rental_duration')->textInput() ?></div>
        </div>
        
        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'price_day')->textInput() ?></div>
            <div class="col-md-3"><?= $form->field($model, 'price_person')->textInput() ?></div>
            <div class="col-md-3"><?= $form->field($model, 'price_hour')->textInput() ?></div>
        </div>

        <?= $this->render('//components/venue_price_ranges.php', ['model'=>$model]) ?>

        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'min_capacity')->textInput() ?></div>
            <div class="col-md-3"><?= $form->field($model, 'max_capacity')->textInput() ?></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'param_spec')->widget(Select2::classname(), [
                    'data' => ViaHelper::getTableMap(VenuesSpec::className()),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Выберите события'), 
                        'value' => explode(',', $model->param_spec),
                        'multiple' => true,
                    ],
                    'pluginOptions' => ['tags' => true, 'allowClear' => true],
                ]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'param_payment_model')->widget(Select2::classname(), [
                    'data' => ViaHelper::getTableMap(RoomsPaymentModel::className()),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Выберите модель оплаты'), 
                        'value' => $model->param_payment_model,
                    ],
                    'pluginOptions' => ['tags' => true, 'allowClear' => true],
                ]) ?>
            </div>
            
        </div>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'param_rent_only')->checkbox([], true) ?>
                <?= $form->field($model, 'param_bright_room')->checkbox([], true) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'param_separate_entrance')->checkbox([], true) ?>
                <?= $form->field($model, 'param_air_conditioner')->checkbox([], true) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'param_area')->textInput() ?></div>
            <div class="col-md-3"><?= $form->field($model, 'param_ceiling_height')->textInput() ?></div>
            <div class="col-md-3"><?= $form->field($model, 'param_floor')->textInput() ?></div>
            <div class="col-md-3"><?= $form->field($model, 'param_total_floors')->textInput() ?></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'param_location')->widget(Select2::classname(), [
                    'data' => ViaHelper::getTableMap(RoomsLocation::className()),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Выберите расположения'), 
                        'value' => explode(',', $model->param_location),
                        'multiple' => true,
                    ],
                    'pluginOptions' => ['tags' => true, 'allowClear' => true],
                ]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'param_features')->widget(Select2::classname(), [
                    'data' => ViaHelper::getTableMap(RoomsFeatures::className()),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Выберите особенности'), 
                        'value' => explode(',', $model->param_features),
                        'multiple' => true,
                    ],
                    'pluginOptions' => ['tags' => true, 'allowClear' => true],
                ]) ?>
            </div>

            <div class="col-md-12"><?= $form->field($model, 'param_name_alt')->textInput() ?></div>
            <div class="col-md-12"><?= $form->field($model, 'param_description')->textarea(['rows' => 6]) ?></div>
            <div class="col-md-12">
                <?= $form->field($model, 'param_zones')->widget(Select2::classname(), [
                    'data' => ViaHelper::getTableMap(RoomsZones::className()),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Выберите функциональные зоны'), 
                        'value' => explode(',', $model->param_zones),
                        'multiple' => true,
                    ],
                    'pluginOptions' => ['tags' => true, 'allowClear' => true],
                ]) ?>
            </div>
        </div>

        <?= $this->render('_form-loft', [ 'model' => $model, 'form' => $form ]) ?>
        <?= $this->render('_form-images', [ 'model' => $model, 'form' => $form ]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
