<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use backend\models\User;
use backend\models\Region;
use backend\models\Agglomeration;
use backend\models\Cities;
use backend\models\District;
use backend\models\VenuesSpec;
use backend\models\VenuesType;
use backend\models\VenuesLocation;
use backend\models\VenuesOwnAlcohol;
use backend\models\VenuesDecorPolicy;
use backend\models\VenuesPayment;
use backend\models\VenuesExtraServices;
use backend\models\VenuesSpecial;
use backend\models\VenuesStatus;
use backend\models\VenuesSeatingArrangement;
use backend\models\VenuesParkingType;
use backend\models\VenuesKitchenType;
use backend\models\ViaHelper;
use kartik\detail\DetailView;

/**
 * @var $this yii\web\View
 * @var $model backend\models\Venues
 * @var $dataProvider yii\data\ActiveDataProvider
*/

$is_create = Yii::$app->controller->action->id === 'create';

$this->title = $is_create ? Yii::t('app', 'Добавление элемента') : $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заведения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-view box box-primary">
    <!--
    <div class="box-header">
        <?//= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?//= 
        //Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
        //    'class' => 'btn btn-danger btn-flat',
        //    'data' => [
        //        'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить элемент?'),
        //        'method' => 'post',
        //    ],
        //]) ?>
    </div>
    -->
    
    <?php $this->beginBlock('main');?>
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
                    'heading'=> $is_create ? Yii::t('app', 'Новое заведение') : Yii::t('app', 'Заведение').' #'.$model->id,
                    'type'=>DetailView::TYPE_PRIMARY,
                    // 'footer' => 'Must be some text/html markup',
                    // 'footerOptions' => [
                    //     'class' => 'some_css_class',
                    //     'tamplate' => '{buttons}',
                    // ]
                ],
                'deleteOptions'=>[ 
                    'url' => Url::to(['venues/ajax-delete', 'id' => $model->id]),
                    'params' => ['id' => $model->id, 'kvdelete'=>true],
                ],
                'container' => ['id'=>'kv-demo'],
                'formOptions' => [
                    'action' => !empty($model->id) ? Url::to(['venues/update', 'id' => $model->id]) : Url::to(['venues/create'])
                ] ,
                'attributes' => [
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Основная информация'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'id',
                        'displayOnly'=>true,
                        'type'=> DetailView::INPUT_HIDDEN,
                    ],
                    'name',
                    [
                        'attribute'=>'site_id',
                        'value'=> $model->site->text ?? null,
                        'displayOnly'=>true,
                        'type'=> DetailView::INPUT_HIDDEN,
                    ], 
                    [
                        'attribute'=>'status_id',
                        'format'=>'raw',
                        'value'=> !empty($model->status) ? Html::tag('span', ViaHelper::getText($model->status), [ 'style' => 'border-radius: 3px; color: #fff; padding: 3px 5px; background-color:'.$model->status->color]) : null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesStatus::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите статус'), 
                                'value' => $model->status_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'is_processed',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->is_processed),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'is_contract_signed',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->is_contract_signed),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'is_phoned',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->is_phoned),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'manager_user_id',
                        'format'=>'raw',
                        'value'=> !empty($model->manager_user_id) ? Html::a($model->managerUser->fullname, ['user/view', 'id'=>$model->manager_user_id], ['target'=>'_blank']) : null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => User::getMap(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите менеджера'), 
                                'value' => $model->manager_user_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'vendor_user_id',
                        'format'=>'raw',
                        'value'=> !empty($model->vendor_user_id) ? Html::a($model->vendorUser->fullname, ['user/view', 'id'=>$model->vendor_user_id], ['target'=>'_blank']) : null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => User::getMap(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите продавца'), 
                                'value' => $model->vendor_user_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'description',
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ],
                    [
                        'attribute'=>'comment',
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ],
                    [
                        'attribute'=>'param_video',
                        'format'=>'raw',
                        'value'=> !empty($model->param_video) ? Html::a(Yii::t('app', 'Ссылка'), $model->param_video, ['target'=>'_blank']) : null,
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>2]
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Контакты'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'phone',
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => 'yii\widgets\MaskedInput',
                            'model' => $model,
                            'attribute' => 'phone',
                            'mask' => '+55 99 99999 9999'
                        ],
                    ],
                    [
                        'attribute'=>'phone2',
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => 'yii\widgets\MaskedInput',
                            'model' => $model,
                            'attribute' => 'phone2',
                            'mask' => '+55 99 99999 9999'
                        ],
                    ],
                    [
                        'attribute'=>'phone_wa',
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class' => 'yii\widgets\MaskedInput',
                            'model' => $model,
                            'attribute' => 'phone_wa',
                            'mask' => '+55 99 99999 9999'
                        ],
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Местоположение'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    'latitude:ntext',
                    'longitude:ntext',
                    [
                        'attribute'=>'agglomeration_id',
                        'format'=>'raw',
                        'value'=> $model->agglomeration->name ?? null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => Agglomeration::getMap(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите агломерацию'), 
                                'value' => $model->agglomeration_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'city_id',
                        'format'=>'raw',
                        'value'=> $model->city->name ?? null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => Cities::getMap(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите город'), 
                                'value' => $model->city_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'region_id',
                        'format'=>'raw',
                        'value'=> $model->region->name ?? null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => Region::getMap(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите округ'), 
                                'value' => $model->region_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'district_id',
                        'format'=>'raw',
                        'value'=> $model->district->name ?? null,
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => District::getMap(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите район'), 
                                'value' => $model->district_id,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'address',
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>2]
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Цены'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],

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
                    
                    ['attribute'=>'param_advanced_payment', 'format' => ['currency', 'BRL']], 
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Параметры заведения'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    'min_capacity',
                    'max_capacity',
                    [
                        'attribute'=>'work_time',
                        'format'=>'raw',
                        'value'=> $model->workingTimeText,
                        'displayOnly'=>true,
                    ],
                    [
                        'attribute'=>'param_spec',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesSpecVias(), 'venuesSpec', ', '),
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
                        'attribute'=>'param_type',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesTypeVias(), 'venuesType', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesType::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите типы заведения'), 
                                'value' => explode(',', $model->param_type),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_location',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesLocationVias(), 'venuesLocation', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesLocation::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите расположения'), 
                                'value' => explode(',', $model->param_location),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_firework',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_firework),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'param_firecrackers',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_firecrackers),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'param_alcohol',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_alcohol),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'param_own_alcohol',
                        'format'=>'raw',
                        'value'=> ViaHelper::getText($model->paramOwnAlcohol),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesOwnAlcohol::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите правило для своего алкоголя'), 
                                'value' => $model->param_own_alcohol,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_decor_policy',
                        'format'=>'raw',
                        'value'=> ViaHelper::getText($model->paramDecorPolicy),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesDecorPolicy::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите правила украшения'), 
                                'value' => $model->param_decor_policy,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ], 
                    [
                        'attribute'=>'param_dj',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_dj),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'param_extra_services',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesExtraServicesVias(), 'venuesExtraServices', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesExtraServices::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите сервисы за дополнительную плату'), 
                                'value' => explode(',', $model->param_extra_services),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'param_payment',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesPaymentVias(), 'venuesPayment', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesPayment::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите способы оплаты'), 
                                'value' => explode(',', $model->param_payment),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'param_bridal_suite',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_bridal_suite),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'param_specials',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesSpecialVias(), 'venuesSpecial', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesSpecial::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите особенности'), 
                                'value' => explode(',', $model->param_specials),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'param_seating_arrangement',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesSeatingArrangementVias(), 'venuesSeatingArrangement', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesSeatingArrangement::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите варианты расстановки столов'), 
                                'value' => explode(',', $model->param_seating_arrangement),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Парковка'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'param_parking_dedicated',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_parking_dedicated),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    'param_parking:ntext',
                    'param_outdoor_capacity:ntext',
                    [
                        'attribute'=>'param_parking_type',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesParkingTypeVias(), 'venuesParkingType', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesParkingType::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите параметры парковки'), 
                                'value' => explode(',', $model->param_parking_type),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Кухня'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    [
                        'attribute'=>'param_kitchen',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_kitchen),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'param_kitchen_type',
                        'format'=>'raw',
                        'value'=> ViaHelper::getArrayText($model->getVenuesKitchenTypeVias(), 'venuesKitchenType', ', '),
                        'type'=> DetailView::INPUT_SELECT2, 
                        'widgetOptions'=>[
                            'data' => ViaHelper::getTableMap(VenuesKitchenType::className()),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите кухни'), 
                                'value' => explode(',', $model->param_kitchen_type),
                                'multiple' => true,
                            ],
                            'pluginOptions' => ['tags' => true, 'allowClear' => true],
                        ],
                    ],
                    [
                        'attribute'=>'param_cuisine',
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ],
                    [
                        'attribute'=>'param_can_order_food',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_can_order_food),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'attribute'=>'param_own_menu',
                        'format'=>'raw',
                        'value'=> ViaHelper::getIntIcon($model->param_own_menu),
                        'type'=> DetailView::INPUT_SWITCH,
                        'widgetOptions' => [
                            'pluginOptions' => [ 'onText' => Yii::t('app', 'Да'), 'offText' => Yii::t('app', 'Нет') ]
                        ],
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Данные из Google'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
                    ],
                    'google_id:ntext',
                    'google_place_id:ntext',
                    [
                        'attribute'=>'google_about',
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ],
                    [
                        'attribute'=>'google_description',
                        'format'=>'raw',
                        'type' => DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ],
                    'google_rating:ntext',
                    'google_reviews:ntext',
                    [
                        'attribute'=>'google_reviews_link',
                        'format'=>'raw',
                        'value'=> !empty($model->google_reviews_link) ? Html::a(Yii::t('app', 'Ссылка'), $model->google_reviews_link, ['target'=>'_blank']) : null,
                    ],
                    [
                        'attribute'=>'google_location_link',
                        'format'=>'raw',
                        'value'=> !empty($model->google_location_link) ? Html::a(Yii::t('app', 'Ссылка'), $model->google_location_link, ['target'=>'_blank']) : null,
                    ],
                    [
                        'group' => true,
                        'label' => Yii::t('app', 'Метаданные'),
                        'rowOptions' => ['class' => DetailView::TYPE_INFO ]
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

    <?php if(Yii::$app->user->can('/venues/update')): ?>
        <?php $this->beginBlock('other');?>
            <div class="box-body">
                <?= $this->render('_form_other.php', ['model'=>$model]) ?>
            </div>
        <?php $this->endBlock();?>
    <?php endif; ?>

    <?php if(Yii::$app->user->can('/venues-visit/index')): ?>
        <?php $this->beginBlock('vendor'); ?>
            <div class="box-body">
                <?php if($is_create): ?>
                    <h4><?= Yii::t('app', 'Чтобы работала вкладка, нужно сохранить элемент.') ?></h4>
                <?php else: ?>
                        <?= $this->render('_form_venue_visit.php', ['model'=>$model]) ?>
                <?php endif; ?>
            </div>
        <?php $this->endBlock(); ?>
    <?php endif; ?>

    <?php if(Yii::$app->user->can('/images/venues')): ?>
        <?php $this->beginBlock('images'); ?>
            <div class="box-body">
                <?php if($is_create): ?>
                    <h4><?= Yii::t('app', 'Чтобы работала вкладка, нужно сохранить элемент.') ?></h4>
                <?php else: ?>
                        <?= $this->render('_gallery', [ 'dataProvider' => $dataProviderImg, 'model' => $model ]) ?>
                <?php endif; ?>
            </div>
        <?php $this->endBlock(); ?>
    <?php endif; ?>
	
    <?php
    $items[] = [
        'content' => $this->blocks['main'],
        'label'   => '<div>'.Yii::t('app','Поля заведения').'<span class="badge badge-default"></span></div>',
        'active'  => true,
    ];
    if(Yii::$app->user->can('/venues/update')){
        $items[] = [
            'content' => $this->blocks['other'],
            'label'   => '<div>'.Yii::t('app','Спаршенные данные').'<span class="badge badge-default"></span></div>',
        ];
    }
    if(Yii::$app->user->can('/venues-visit/index')){
        $items[] = [
            'content' => $this->blocks['vendor'],
            'label'   => '<div>'.Yii::t('app', 'Для менеджеров по продажам').'<span class="badge badge-default"></span></div>',
        ];
    }
    if(Yii::$app->user->can('/images/venues')){
        $items[] = [
            'content' => $this->blocks['images'],
            'label'   => '<div>'.Yii::t('app', 'Изображения').'<span class="badge badge-default"></span></div>',
        ];
    }
    echo Tabs::widget([
		'encodeLabels' => false,
		'items' => $items
	]); 
    ?>

</div>
