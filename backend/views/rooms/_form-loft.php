<?php

use yii\web\JsExpression;
use backend\models\RoomsLoftEntrance;
use backend\models\RoomsLoftStyle;
use backend\models\RoomsLoftColor;
use backend\models\RoomsLoftLight;
use backend\models\RoomsLoftInterior;
use backend\models\RoomsLoftEquipmentFurniture;
use backend\models\RoomsLoftEquipmentInterior;
use backend\models\RoomsLoftEquipment1;
use backend\models\RoomsLoftEquipment2;
use backend\models\RoomsLoftEquipment3;
use backend\models\RoomsLoftEquipmentGames;
use backend\models\RoomsLoftStaff;
use backend\models\ViaHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */
?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'is_loft')->checkbox([
            'onchange' => new JsExpression('
                function handleCheckboxChange(){
                    let isChecked = $(this).prop("checked");
                    let $block = $("[data-loft-props]");
                    if (isChecked) $block.show();
                    else $block.hide();
                }
                handleCheckboxChange.call(this);
            ')
        ]) ?>
    </div>
</div>



<div data-loft-props style="<?= !$model->is_loft ? 'display: none;' : '' ?>">
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'loft_food_catering')->checkbox([], true) ?>
            <?= $form->field($model, 'loft_food_catering_order')->checkbox([], true) ?>
            <?= $form->field($model, 'loft_food_order')->checkbox([], true) ?>
            <?= $form->field($model, 'loft_food_can_cook')->checkbox([], true) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'loft_alcohol_allow')->checkbox([], true) ?>
            <?= $form->field($model, 'loft_alcohol_order')->checkbox([], true) ?>
            <?= $form->field($model, 'loft_alcohol_self')->checkbox([], true) ?>
            <?= $form->field($model, 'loft_alcohol_fee')->checkbox([], true) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'loft_entrance')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftEntrance::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите входы и выходы'), 
                    'value' => explode(',', $model->loft_entrance),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?> 
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_style')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftStyle::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите стили'), 
                    'value' => explode(',', $model->loft_style),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_color')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftColor::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите цвета'), 
                    'value' => explode(',', $model->loft_color),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_light')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftLight::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите освещения'), 
                    'value' => explode(',', $model->loft_light),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_interior')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftInterior::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите особенности интерьера'), 
                    'value' => explode(',', $model->loft_interior),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_equipment_furniture')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftEquipmentFurniture::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите мебель'), 
                    'value' => explode(',', $model->loft_equipment_furniture),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_equipment_interior')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftEquipmentInterior::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите предметы интерьера'), 
                    'value' => explode(',', $model->loft_equipment_interior),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_equipment1')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftEquipment1::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите технику и другое оборудование'), 
                    'value' => explode(',', $model->loft_equipment1),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_equipment2')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftEquipment2::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите принадлежности для еды и напитков'), 
                    'value' => explode(',', $model->loft_equipment2),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_equipment_games')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftEquipmentGames::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите игры'), 
                    'value' => explode(',', $model->loft_equipment_games),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_equipment_3')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftEquipment3::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите профессиональное оборудование'), 
                    'value' => explode(',', $model->loft_equipment_3),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'loft_staff')->widget(Select2::classname(), [
                'data' => ViaHelper::getTableMap(RoomsLoftStaff::className()),
                'options' => [
                    'placeholder' => Yii::t('app', 'Выберите персонал'), 
                    'value' => explode(',', $model->loft_staff),
                    'multiple' => true,
                ],
                'pluginOptions' => ['tags' => true, 'allowClear' => true],
            ]) ?>
        </div>
    </div>
</div>