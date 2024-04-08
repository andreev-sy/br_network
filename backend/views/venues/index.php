<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use kartik\grid\GridView;
use backend\models\ViaHelper;
use backend\widgets\GridViewSort;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\VenuesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Заведения');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="venues-index box box-primary">
    <?//php Pjax::begin(['id'=>'venues-index']); ?>
    <div class="box-header with-border">
        <?php if(Yii::$app->user->can('/rooms/create')): ?>
            <?= Html::a(Yii::t('app', 'Добавить элемент'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php endif; ?>
        <?= Html::button(Yii::t('app', 'Показать фильтр'), [
            'class' => 'btn',
            'data-text-alt' => Yii::t('app', 'Скрыть фильтр'),
            'onclick' => new JsExpression('
                function handleShowFilter(){
                    let $btn = $(this);
                    let text_alt = $btn.data("text-alt");
                    $btn.data("text-alt", $btn.text());
                    $btn.text(text_alt);
                    $("[data-filter]").slideToggle("slow");
                }
                handleShowFilter.call(this);
            ')
        ]) ?>
        <?= GridViewSort::widget(['model' => $searchModel]); ?>
    </div>

    <div class="box-header">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div> 
    <div class="box-body">
        <?=
        GridView::widget([
            'dataProvider'=> $dataProvider,
            // 'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'expandIcon' => '<i class="fa fa-caret-right"></i>',
                    'collapseIcon' => '<i class="fa fa-caret-down"></i>',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => Url::to(['venues/ajax-get-details']),
                ],
                [ 'attribute' => 'id',  'width' => '0px' ],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function($data){
                        return Html::a($data->name, ['venues/view', 'id'=>$data->id], ['target'=>'_blank']);
                    },
                ],
                [
                    'attribute' => 'region_id',
                    'value' => 'region.name',
                ],
                [
                    'attribute' => 'district_id',
                    'value' => 'district.name',
                ],
                [
                    'attribute' => 'prices',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget) {
                        $prices = [
                            'price_day' => $model->getAttributeLabel('prices_day').': '.Yii::$app->formatter->asCurrency($model->price_day, 'BRL'),
                            'price_hour' => $model->getAttributeLabel('prices_hour').': '.Yii::$app->formatter->asCurrency($model->price_hour, 'BRL'),
                            'price_person' => $model->getAttributeLabel('prices_person').': '.Yii::$app->formatter->asCurrency($model->price_person, 'BRL'),
                        ];
                        
                        return Html::tag('small', implode('<br>', $prices));
                    },
                ],
                [
                    'attribute' => 'capacity',
                    'value' => function ($model, $key, $index, $widget) { 
                        return $model->min_capacity.' - '.$model->max_capacity;
                    },
                ],
                [
                    'attribute' => 'flags',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget) { 
                        $flags = [
                            'is_processed' => $model->getAttributeLabel('is_processed').': '.ViaHelper::getIntIcon($model->is_processed),
                            'is_phoned' => $model->getAttributeLabel('is_phoned').': '.ViaHelper::getIntIcon($model->is_phoned),
                            'is_contract_signed' => $model->getAttributeLabel('is_contract_signed').': '.ViaHelper::getIntIcon($model->is_contract_signed),
                        ];
                        
                        return Html::tag('small', implode('<br>', $flags));
                    },
                ],
                [
                    'attribute' => 'users',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget) { 
                        $users = [
                            'manager_user_id' => $model->getAttributeLabel('manager_user_id').': '.($model->managerUser->fullname ?? null),
                            'vendor_user_id' => $model->getAttributeLabel('vendor_user_id').': '.($model->vendorUser->fullname ?? null),
                        ];
                        
                        return Html::tag('small', implode('<br>', $users));
                    },
                ],
                // 'site_id',
                // 'city_id',
                // 'address:ntext',
                // 'price_day:ntext',
                // 'price_person:ntext',
                // 'price_hour:ntext',
                // 'price_day_ranges:ntext',
                // 'work_time:ntext',
                // 'phone',
                // 'phone2',
                // 'phone_wa',
                // 'param_spec:ntext',
                // 'description:ntext',
                // 'comment:ntext',
                [
                    'attribute' => 'status_id', 
                    'format' => 'raw',
                    'width' => '0px',
                    'value' => function ($model, $key, $index, $widget) { 
                        return !empty($model->status) ? Html::tag('span', ViaHelper::getText($model->status), [ 'style' => 'border-radius: 3px; color: #fff; padding: 3px 5px; background-color:'.$model->status->color]) : null;
                    },
                ],
                // 'param_type:ntext',
                // 'param_location:ntext',
                // 'param_kitchen',
                // 'param_kitchen_type:ntext',
                // 'param_cuisine:ntext',
                // 'param_advanced_payment:ntext',
                // 'param_firework',
                // 'param_firecrackers',
                // 'param_parking_dedicated',
                // 'param_parking:ntext',
                // 'param_outdoor_capacity:ntext',
                // 'param_alcohol',
                // 'param_own_alcohol',
                // 'param_decor_policy',
                // 'param_dj',
                // 'param_extra_services:ntext',
                // 'param_bridal_suite',
                // 'param_payment:ntext',
                // 'param_can_order_food',
                // 'param_own_menu',
                // 'param_specials:ntext',
                // 'param_seating_arrangement:ntext',
                // 'param_parking_type:ntext',
                // 'param_video:ntext',
                // 'latitude:ntext',
                // 'longitude:ntext',
                // 'google_id:ntext',
                // 'google_place_id:ntext',
                // 'google_about:ntext',
                // 'google_description:ntext',
                // 'google_rating',
                // 'google_reviews',
                // 'google_reviews_link:ntext',
                // 'google_location_link:ntext',
                // 'created_at',
                // 'updated_at',
                ['class' => '\kartik\grid\ActionColumn', 'template' => Yii::$app->RbacActionTemplate::check(null, '{view} {delete}')],
            ],
            // 'pjax'=>true,
            // 'pjaxSettings'=>[
            //     'neverTimeout'=>true,
            //     // 'beforeGrid'=>'My fancy content before.',
            //     // 'afterGrid'=>'My fancy content after.',
            // ]
        ]);
        ?>
    </div>
    <?//php Pjax::end(); ?>
</div>