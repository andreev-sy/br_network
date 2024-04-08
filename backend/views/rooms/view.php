<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;
use backend\models\ViaHelper;
use backend\models\Venues;
use backend\models\RoomsLocation;
use backend\models\RoomsFeatures;
use backend\models\VenuesSpec;
use backend\models\RoomsZones;
use backend\models\RoomsPaymentModel;
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
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */

$is_create = Yii::$app->controller->action->id === 'create';

if($is_create) $this->title = Yii::t('app', 'Добавление элемента');
else $this->title = !empty($model->param_name_alt) ? $model->param_name_alt : Yii::t('app', 'Зал').' #'.$model->id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Залы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-view box box-primary">
    <!--
    <div class="box-header">
        <?//= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?//= 
          //  Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
          //      'class' => 'btn btn-danger btn-flat',
          //      'data' => [ 'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить элемент?'), 'method' => 'post' ],
          //  ]) 
        ?>
    </div>
    -->

    <?php $this->beginBlock('main'); ?>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'condensed' => true,
                'hover' => true,
                'striped' => false,
                'buttons1' => Yii::$app->RbacActionTemplate::check(null, '{update} {delete}'),
                'buttons2' => $is_create ? '{reset} {save}' : '{view} {reset} {save}',
                'mode' => $is_create ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
                'fadeDelay'=>200,
                'panel' => [
                    'heading'=> $is_create ? Yii::t('app', 'Новый зал') : Yii::t('app', 'Зал').' #'.$model->id,
                    'type'=>DetailView::TYPE_PRIMARY,
                ],
                'deleteOptions'=>[
                    'url' => Url::to(['rooms/ajax-delete', 'id' => $model->id]),
                    'params' => ['id' => $model->id, 'kvdelete'=>true],
                ],
                'container' => ['id'=>'kv-room'],
                'formOptions' => [
                    'action' => !empty($model->id) ? Url::to(['rooms/update', 'id' => $model->id]) : Url::to(['rooms/create'])
                ],
                'attributes' => [
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Основная информация'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'id',
                        'displayOnly'=>true,
                        'type' => DetailView::INPUT_HIDDEN,
                    ],
                    [
                        'attribute'=>'venue_id',
                        'format'=>'raw',
                        'value'=> !empty($model->venue) ? Html::a("(#{$model->venue_id}) {$model->venue->name}", ['venues/view', 'id'=>$model->venue_id], ['target'=>'_blank']) : null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => Venues::getMap(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите заведение'), 
                                'value' => $model->venue_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    'param_name_alt:ntext',
                    [
                        'attribute'=>'param_description',
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Аренда'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'param_rent_only',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_rent_only),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    'param_minimum_rental_duration',
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Цены'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    ['attribute'=>'param_min_price', 'format' => ['currency', 'BRL']], 
                    ['attribute'=>'price_day', 'format' => ['currency', 'BRL']], 
                    ['attribute'=>'price_person', 'format' => ['currency', 'BRL']], 
                    ['attribute'=>'price_hour', 'format' => ['currency', 'BRL']], 
                    [
                        'attribute'=>'price_day_ranges',
                        'format'=>'raw',
                        'value'=> $model->priceDayRangesList,
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => 'backend\widgets\PriceRange',
                            'model' => $model,
                            'attribute' => 'price_day_ranges',
                        ],
                    ], 
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Параметры помещения'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    'min_capacity',
                    'max_capacity',
                    [
                        'attribute'=>'param_bright_room',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_bright_room),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'param_separate_entrance',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_separate_entrance),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'param_air_conditioner',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_air_conditioner),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    'param_area:ntext',
                    'param_ceiling_height:ntext',
                    'param_floor',
                    'param_total_floors',
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Другие параметры'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'param_location',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLocationVias(), 'roomsLocation', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLocation::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите расположения'), 
                                'value' => explode(',', $model->param_location),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_features',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsFeaturesVias(), 'roomsFeatures', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsFeatures::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите особенности'), 
                                'value' => explode(',', $model->param_features),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_zones',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsZonesVias(), 'roomsZones', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsZones::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите функциональные зоны'), 
                                'value' => explode(',', $model->param_zones),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_spec',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsVenuesSpecVias(), 'venuesSpec', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesSpec::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите мероприятия'), 
                                'value' => explode(',', $model->param_spec),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_payment_model',
                        'format'=>'raw',
                        'value'=> ViaHelper::getText($model->paramPaymentModel),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsPaymentModel::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите модель оплаты'), 
                                'value' => explode(',', $model->param_payment_model),
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Лофт'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'is_loft',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->is_loft),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_food_catering',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_food_catering),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_food_catering_order',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_food_catering_order),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_food_order',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_food_order),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_food_can_cook',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_food_can_cook),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_alcohol_allow',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_alcohol_allow),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_alcohol_order',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_alcohol_order),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_alcohol_self',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_alcohol_self),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_alcohol_fee',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->loft_alcohol_fee),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ], 
                    [
                        'attribute'=>'loft_entrance',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftEntranceVias(), 'roomsLoftEntrance', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftEntrance::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите входы и выходы'), 
                                'value' => explode(',', $model->loft_entrance),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_style',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftStyleVias(), 'roomsLoftStyle', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftStyle::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите стили'), 
                                'value' => explode(',', $model->loft_style),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_color',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftColorVias(), 'roomsLoftColor', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftColor::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите цвета'), 
                                'value' => explode(',', $model->loft_color),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_light',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftLightVias(), 'roomsLoftLight', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftLight::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите освещения'), 
                                'value' => explode(',', $model->loft_light),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_interior',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftInteriorVias(), 'roomsLoftInterior', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftInterior::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите особенности интерьера'), 
                                'value' => explode(',', $model->loft_interior),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_equipment_furniture',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftEquipmentFurnitureVias(), 'roomsLoftEquipmentFurniture', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftEquipmentFurniture::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите мебель'), 
                                'value' => explode(',', $model->loft_equipment_furniture),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_equipment_interior',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftEquipmentInteriorVias(), 'roomsLoftEquipmentInterior', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftEquipmentInterior::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите предметы интерьера'), 
                                'value' => explode(',', $model->loft_equipment_interior),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_equipment1',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftEquipment1Vias(), 'roomsLoftEquipment1', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftEquipment1::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите технику и другое оборудование'), 
                                'value' => explode(',', $model->loft_equipment1),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_equipment2',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftEquipment2Vias(), 'roomsLoftEquipment2', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftEquipment2::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите принадлежности для еды и напитков'), 
                                'value' => explode(',', $model->loft_equipment2),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_equipment_games',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftEquipmentGamesVias(), 'roomsLoftEquipmentGames', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftEquipmentGames::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите игры'), 
                                'value' => explode(',', $model->loft_equipment_games),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_equipment_3',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftEquipment3Vias(), 'roomsLoftEquipment3', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftEquipment3::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите профессиональное оборудование'), 
                                'value' => explode(',', $model->loft_equipment_3),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'loft_staff',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getRoomsLoftStaffVias(), 'roomsLoftStaff', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(RoomsLoftStaff::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите персонал'), 
                                'value' => explode(',', $model->loft_staff),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Метаданные'),
                        'rowOptions' => [
                            'class' => DetailView::TYPE_INFO,
                            'style' => $is_create ? 'display: none;' : ''
                        ],
                    ],
                    [
                        'attribute'=>'created_at',
                        'format'=>'datetime',
                        'displayOnly'=>true,
                        'type'=> $is_create ? DetailView::INPUT_HIDDEN : null,
                    ],
                    [
                        'attribute'=>'updated_at',
                        'format'=>'datetime',
                        'displayOnly'=>true,
                        'type'=> $is_create ? DetailView::INPUT_HIDDEN : null,
                    ],
                ]
            ]) ?>
        </div>
    <?php $this->endBlock(); ?>

    <?php if(Yii::$app->user->can('/images/rooms')): ?>
        <?php $this->beginBlock('images'); ?>
            <div class="box-body">
                <?php if($is_create): ?>
                    <h4><?= Yii::t('app', 'Чтобы работала вкладка, нужно сохранить элемент.') ?></h4>
                <?php else: ?>
                    <?php 
                    $form = ActiveForm::begin(['action' => ['rooms/image-upload', 'id' => $model->id]]);
                    echo $this->render('_form-images', ['model' => $model, 'form' => $form]);
                    ActiveForm::end(); 
                    ?>
                <?php endif; ?>
            </div>
        <?php $this->endBlock(); ?>
    <?php endif; ?>

    <?php
    $items[] = [
        'content' => $this->blocks['main'],
        'label' => '<div>' . Yii::t('app', 'Поля зала') . '<span class="badge badge-default"></span></div>',
        'active' => true,
    ];
    if(Yii::$app->user->can('/images/rooms')){
        $items[] = [
            'content' => $this->blocks['images'],
            'label' => '<div>' . Yii::t('app', 'Изображения') . '<span class="badge badge-default"></span></div>',
        ];
    }
    echo Tabs::widget([
        'encodeLabels' => false,
        'items' => $items
    ]); 
    ?>

</div>