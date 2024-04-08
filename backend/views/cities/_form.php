<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Agglomeration;

/* @var $this yii\web\View */
/* @var $model backend\models\Cities */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cities-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?> 
        <?= $form->field($model, 'agglomeration_id')->widget(Select2::classname(), [
            'data' => Agglomeration::getMap(),
            'options' => [
                'placeholder' => Yii::t('app', 'Выберите агломерацию'), 
                'value' => $model->agglomeration_id,
            ],
            'pluginOptions' => ['tags' => true, 'allowClear' => true],
        ]) ?> 
    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
