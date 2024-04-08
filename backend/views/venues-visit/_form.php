<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Venues;
use backend\models\VenuesVisitStatus;
use backend\models\ViaHelper;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesVisit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="venues-visit-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'venue_id')->widget(Select2::classname(), [
            'data' => Venues::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите заведение'), 
                'value' => $model->venue_id,
            ],
            'pluginOptions' => ['tags' => true],
        ]) ?>
        <?= $form->field($model, 'person')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'phone')->widget(MaskedInput::class, ['mask' => '+55 99 99999 9999',]) ?>
        <?= $form->field($model, 'phone_wa')->widget(MaskedInput::class, ['mask' => '+55 99 99999 9999',]) ?>
        <?= $form->field($model, 'status_id')->dropDownList(ViaHelper::getTableMap(VenuesVisitStatus::className())) ?>
        <?= $form->field($model, 'count_banquets')->textInput() ?>
        <?= $form->field($model, 'amount_commission')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
