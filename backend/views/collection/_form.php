<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\jui\DatePicker;
use yii\widgets\MaskedInput;
use backend\models\User;
use backend\models\Collection;
use backend\models\CollectionSpec;
use backend\models\Region;
use backend\models\District;
use backend\models\CollectionPricePerson;
use backend\models\CollectionContactType;
use backend\models\CollectionGuest;
use backend\models\FormRequest;
use backend\models\Agglomeration;
use backend\models\Cities;
use backend\models\ViaHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
 
        <?php
        $select_event = <<< JS
            function() {
                let form_request_id = $(this).val();
                $.ajax({
                    url: '/collection/ajax-get-data/',
                    method: 'POST',
                    data: { form_request_id },
                    success: function (response) { 
                        let data = JSON.parse(response);
                        
                        $('#collection-guest').find('option[selected]').removeAttr('selected');
                        $('#collection-price_person_id').find('option[selected]').removeAttr('selected');
                        $('#collection-contact_type_id').find('option[selected]').removeAttr('selected');

                        $('#collection-name').val(data.name);
                        $('#collection-date').val(data.date);
                        $('#collection-phone').val(data.phone);
                        $("#collection-guest_id").val(data.guest_id).trigger('change');
                        $("#collection-price_person_id").val(data.price_person_id).trigger('change');
                        $("#collection-contact_type_id").val(data.contact_type_id).trigger('change');
                        $("#collection-spec_id").val(data.spec_id).trigger('change');
                        $("#collection-regionselection").val(data.regionSelection).trigger('change');
                    },
                    error: function (xhr, status, error) { console.log(error); }
                });
            }
        JS;
        ?>
        <?= $form->field($model, 'form_request_id')->widget(Select2::classname(), [
            'id' => 'form_request_id',
            'data' => FormRequest::getMap(),
            'options' => ['value' => $model->form_request_id, 'placeholder' => Yii::t('app', 'Выберите заявку')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
            'pluginEvents' => [ 'change' => new JsExpression($select_event) ]
        ]);	?> 

        <?= $form->field($model, 'manager_user_id')->widget(Select2::classname(), [
            'data' => User::getMap(),
            'options' => ['value' => $model->manager_user_id, 'placeholder' => Yii::t('app', 'Выберите менеджера')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <div style="display:none"><?= $form->field($model, 'is_form')->hiddenInput(['value' => 1]) ?></div>
        <?= $form->field($model, 'date')->widget(MaskedInput::class, [ 'mask' => '99/99/9999' ]); ?>
        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [ 'mask' => '+55 99 99999 9999' ]); ?>

        <?= $form->field($model, 'spec_id')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(CollectionSpec::className()),
            'options' => ['value' => $model->spec_id, 'placeholder' => Yii::t('app', 'Выберите мероприятие')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'guest_id')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(CollectionGuest::className()),
            'options' => ['value' => $model->guest_id, 'placeholder' => Yii::t('app', 'Выберите количество гостей')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'price_person_id')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(CollectionPricePerson::className()),
            'options' => ['value' => $model->price_person_id, 'placeholder' => Yii::t('app', 'Выберите цену за человека')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>
 
        <?= $form->field($model, 'contact_type_id')->widget(Select2::classname(), [
            'data' => ViaHelper::getTableMap(CollectionContactType::className()),
            'options' => ['value' => $model->contact_type_id, 'placeholder' => Yii::t('app', 'Выберите тип контакта')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'agglomeration_id')->widget(Select2::classname(), [
            'data' => Agglomeration::getMap(),
            'options' => ['value' => $model->agglomeration_id, 'placeholder' => Yii::t('app', 'Выберите агломерацию')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
            'data' => Cities::getMap(),
            'options' => ['value' => $model->city_id, 'placeholder' => Yii::t('app', 'Выберите город')],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'region_ids')->widget(Select2::classname(), [
            'data' => Region::getMap(),
            'options' => ['value' => ArrayHelper::getColumn($model->collectionRegionVias, 'region_id'), 'placeholder' => Yii::t('app', 'Выберите округ'), 'multiple'=>true],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'district_ids')->widget(Select2::classname(), [
            'data' => District::getMap(),
            'options' => ['value' => ArrayHelper::getColumn($model->collectionDistrictVias, 'district_id'), 'placeholder' => Yii::t('app', 'Выберите район'), 'multiple'=>true],
            'pluginOptions' => [ 'tags' => true, 'allowClear' => true ],
        ]);	?>

        <?= $form->field($model, 'desire')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'pool')->checkbox() ?>
        <?= $form->field($model, 'place_barbecue')->checkbox() ?>
        <?= $form->field($model, 'open_area')->checkbox() ?>

        <?//= $form->field($model, 'hash')->textarea(['rows' => 6]) ?>
        <?//= $form->field($model, 'created_at')->textInput() ?>
        <?//= $form->field($model, 'updated_at')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
