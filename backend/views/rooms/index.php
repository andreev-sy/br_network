<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\models\Rooms;
use backend\models\ViaHelper;
use kartik\grid\GridView;
use yii\web\JsExpression;
use backend\widgets\GridViewSort;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RoomsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Залы');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="rooms-index box box-primary">
    <?//php  Pjax::begin(); ?>
    <div class="box-header with-border">
        <?php if(Yii::$app->user->can('/venues/create')): ?>
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
        <?//= $this->render('//components/filter_settings.php', ['model' => new Rooms()]);  ?>
    </div>

    <div class="box-header">
        <?php echo $this->render('_search', ['model' => $searchModel]);  ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
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
                    'detailUrl' => Url::to(['rooms/ajax-get-details']),
                ],
                [ 'attribute' => 'id',  'width' => '0px' ],
                [
                    'attribute' => 'param_name_alt', 
                    'format' => 'raw',
                    'width' => '0px',
                    'value' => function ($model, $key, $index, $widget) { 
                        return Html::a($model->param_name_alt, ['rooms/view', 'id'=>$model->id], ['target'=>'_blank']);
                    },
                ],
                [
                    'attribute' => 'venue_id', 
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget) { 
                        return Html::a("(#{$model->venue->id}) {$model->venue->name}", ['venues/view', 'id'=>$model->venue->id], ['target'=>'_blank']);
                    },
                ],

                'param_minimum_rental_duration:ntext',
                'param_min_price:ntext',
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
                    'attribute' => 'is_loft', 
                    'format' => 'raw',
                    'width' => '0px',
                    'value' => function ($model, $key, $index, $widget) { 
                        return ViaHelper::getIntIcon($model->is_loft);
                    },
                ],
                // 'price_day_ranges:ntext',
                // 'param_spec:ntext',
                // 'param_payment_model',
                // 'param_rent_only',
                // 'param_bright_room',
                // 'param_separate_entrance',
                // 'param_air_conditioner',
                // 'param_area:ntext',
                // 'param_ceiling_height:ntext',
                // 'param_floor',
                // 'param_total_floors',
                // 'param_location:ntext',
                // 'param_features:ntext',
                // 'param_name_alt:ntext',
                // 'param_description:ntext',
                // 'param_zones:ntext',
                // 'is_loft',
                // 'loft_food_catering',
                // 'loft_food_catering_order',
                // 'loft_food_order',
                // 'loft_food_can_cook',
                // 'loft_alcohol_allow',
                // 'loft_alcohol_order',
                // 'loft_alcohol_self',
                // 'loft_alcohol_fee',
                // 'loft_entrance:ntext',
                // 'loft_style:ntext',
                // 'loft_color:ntext',
                // 'loft_light:ntext',
                // 'loft_interior:ntext',
                // 'loft_equipment_furniture:ntext',
                // 'loft_equipment_interior:ntext',
                // 'loft_equipment1:ntext',
                // 'loft_equipment2:ntext',
                // 'loft_equipment_games:ntext',
                // 'loft_equipment_3:ntext',
                // 'loft_staff:ntext',
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
        ]); ?>
    </div>
    <?//php Pjax::end(); ?>
</div>