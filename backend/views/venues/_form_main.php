<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use backend\models\User;
use backend\models\VenuesSpec;
use backend\models\VenuesStatus;
use backend\models\VenuesType;
use backend\models\VenuesLocation;
use backend\models\VenuesKitchenType;
use backend\models\VenuesOwnAlcohol;
use backend\models\VenuesDecorPolicy;
use backend\models\VenuesExtraServices;
use backend\models\VenuesPayment;
use backend\models\VenuesSpecial;
use backend\models\VenuesSeatingArrangement;
use backend\models\VenuesParkingType;
use backend\models\District;
use backend\models\Cities;
use backend\models\Agglomeration;
use backend\models\ViaHelper;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?//= $form->field($model, 'site_id')->textInput() ?>

        <?= $form->field($model, 'status_id')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesStatus::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите статус'), 
                'value' => $model->status_id,
            ],
            'pluginOptions' => ['tags' => true],
        ]) ?>

        <?= $form->field($model, 'agglomeration_id')->widget(Select2::classname(), [
            'data' => Agglomeration::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите агломерацию'), 
                'value' => $model->agglomeration_id,
            ],
            'pluginOptions' => ['tags' => true],
        ]) ?>

        <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
            'data' => Cities::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите город'), 
                'value' => $model->city_id,
            ],
            'pluginOptions' => ['tags' => true],
        ]) ?>

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'district_id')->widget(Select2::classname(), [
            'data' => District::getMapViaCity(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите район'), 
                'value' => $model->district_id,
            ],
            'pluginOptions' => ['tags' => true],
        ]) ?>


        <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'price_day')->textInput() ?>
        <?= $form->field($model, 'price_person')->textInput() ?>
        <?= $form->field($model, 'price_hour')->textInput() ?>

        <?= $this->render('//components/venue_price_ranges.php', ['model'=>$model]) ?>
        
        <?= $form->field($model, 'work_time')->textInput() ?>
        <?= $form->field($model, 'min_capacity')->textInput() ?>
        <?= $form->field($model, 'max_capacity')->textInput() ?>
        <?= $form->field($model, 'phone')->widget(MaskedInput::class, ['mask' => '+55 99 99999 9999',]) ?>
        <?= $form->field($model, 'phone2')->widget(MaskedInput::class, ['mask' => '+55 99 99999 9999',]) ?>
        <?= $form->field($model, 'phone_wa')->widget(MaskedInput::class, ['mask' => '+55 99 99999 9999',]) ?>

        <?= $form->field($model, 'param_spec')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesSpec::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите события'), 
                'value' => explode(',', $model->param_spec),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>


        <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'comment')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'manager_user_id')->widget(Select2::classname(), [
            'data' => User::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите менеджера'), 
                'value' => $model->manager_user_id,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'vendor_user_id')->widget(Select2::classname(), [
            'data' => User::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите продавца'), 
                'value' => $model->vendor_user_id,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>


        <?= $form->field($model, 'is_processed')->checkbox([], true) ?>

        <?= $form->field($model, 'is_contract_signed')->checkbox([], true) ?>

        <?= $form->field($model, 'is_phoned')->checkbox([], true) ?>

        <br>
        <h4><?= Yii::t('app', 'Новые поля')?></h4>
        <hr>

        <?= $form->field($model, 'param_type')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesType::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите типы заведения'), 
                'value' => explode(',', $model->param_type),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'param_location')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesLocation::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите расположения'), 
                'value' => explode(',', $model->param_location),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'param_kitchen')->checkbox([], true) ?>

        <?= $form->field($model, 'param_kitchen_type')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesKitchenType::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите кухни'), 
                'value' => explode(',', $model->param_kitchen_type),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'param_cuisine')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'param_advanced_payment')->textInput() ?>
        <?= $form->field($model, 'param_firework')->checkbox([], true) ?>
        <?= $form->field($model, 'param_firecrackers')->checkbox([], true) ?>
        <?= $form->field($model, 'param_parking_dedicated')->checkbox([], true) ?>
        <?= $form->field($model, 'param_parking')->textInput() ?>
        <?= $form->field($model, 'param_outdoor_capacity')->textInput() ?>
        <?= $form->field($model, 'param_alcohol')->checkbox([], true) ?>

        <?= $form->field($model, 'param_own_alcohol')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesOwnAlcohol::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите правило для своего алкоголя'), 
                'value' => $model->param_own_alcohol,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>
        
        <?= $form->field($model, 'param_decor_policy')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesDecorPolicy::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите правила украшения'), 
                'value' => $model->param_decor_policy,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>


        <?= $form->field($model, 'param_dj')->checkbox([], true) ?>

        <?= $form->field($model, 'param_extra_services')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesExtraServices::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите сервисы за дополнительную плату'), 
                'value' => explode(',', $model->param_extra_services),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>


        <?= $form->field($model, 'param_bridal_suite')->checkbox([], true) ?>


        <?= $form->field($model, 'param_payment')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesPayment::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите способы оплаты'), 
                'value' => explode(',', $model->param_payment),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>


        <?= $form->field($model, 'param_can_order_food')->checkbox([], true) ?>
        <?= $form->field($model, 'param_own_menu')->checkbox([], true) ?>

        <?= $form->field($model, 'param_specials')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesSpecial::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите особенности'), 
                'value' => explode(',', $model->param_specials),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'param_seating_arrangement')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesSeatingArrangement::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите варианты расстановки столов'), 
                'value' => explode(',', $model->param_seating_arrangement),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'param_parking_type')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(VenuesParkingType::className()),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите параметры парковки'), 
                'value' => explode(',', $model->param_parking_type),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>


        <?= $form->field($model, 'param_video')->textInput() ?>

        <?= $form->field($model, 'latitude')->textInput() ?>
        <?= $form->field($model, 'longitude')->textInput() ?>
        <?= $form->field($model, 'google_id')->textInput() ?>
        <?= $form->field($model, 'google_place_id')->textInput() ?>
        <?= $form->field($model, 'google_about')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'google_description')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'google_rating')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'google_reviews')->textInput() ?>
        <?= $form->field($model, 'google_reviews_link')->textInput() ?>
        <?= $form->field($model, 'google_location_link')->textInput() ?>
    
    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
<?php ActiveForm::end(); ?>