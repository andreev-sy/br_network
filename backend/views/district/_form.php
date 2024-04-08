<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Agglomeration;
use backend\models\Region;

/* @var $this yii\web\View */
/* @var $model backend\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'agglomeration_id')->widget(Select2::classname(), [
            'data' => Agglomeration::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите агломерацию'), 
                'value' => $model->agglomeration_id,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'region_ids')->widget(Select2::classname(), [
            'data' => Region::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите районы'), 
                'value' => ArrayHelper::getColumn($model->districtRegionVias, 'region_id'),
                'multiple' => true,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
 