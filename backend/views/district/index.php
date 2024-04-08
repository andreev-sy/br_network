<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Region;
use kartik\select2\Select2;
use backend\models\Agglomeration;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DistrictSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Районы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-index box box-primary">
    <?php Pjax::begin(['id' => 'district-index']); ?>
    <?php if(Yii::$app->user->can('/district/create')): ?>
        <div class="box-header with-border">
            <?= Html::a(Yii::t('app', 'Добавить элемент'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
    <?php endif; ?>

    <div class="box-header">
        <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
        
                'id',
                'name:ntext',
                [
                    'attribute' => 'agglomeration_id',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'agglomeration_id',
                        'data' => Agglomeration::getMap(),
                        'options' => ['placeholder' => Yii::t('app', 'Выберите агломерацию')],
                        'pluginOptions' => ['allowClear' => true, 'tags' => true],
                    ]),
                    'value' => function ($data) {
                        return Select2::widget([
                            'name' => 'agglomeration_id',
                            'data' => Agglomeration::getMap(),
                            'value' => $data->agglomeration_id,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите агломерацию'), 
                                'data-district' => $data->id,
                                'disabled' => !Yii::$app->user->can('/district/ajax-set-agglomeration')
                            ],
                            'pluginEvents' => [
                                'change' => new JsExpression('function() {
                                    $.post( "/district/ajax-set-agglomeration/", { district_id: $(this).data("district"), agglomeration_id: $(this).val() }, function(){
                                        $.pjax.reload({container:"#district-index"}); 
                                    })
                                }'),
                            ]
                        ]);
                    }
                ],
                [
                    'attribute' => 'region_id',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'region_id',
                        'data' => Region::getMap(),
                        'options' => ['placeholder' => Yii::t('app', 'Выберите округ')],
                        'pluginOptions' => ['allowClear' => true, 'tags' => true],
                    ]),
                    'value' => function ($data) {
                        $regions = !empty ($data->city_id) ? Region::findAll(['city_id' => $data->city_id]) : Region::find()->all();
                        return Select2::widget([
                            'name' => 'region_ids',
                            'data' => ArrayHelper::map($regions, 'id', 'name'),
                            'value' => ArrayHelper::getColumn($data->districtRegionVias, 'region_id'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Выберите округ'), 
                                'data-district' => $data->id, 
                                'multiple' => true,
                                'disabled' => !Yii::$app->user->can('/district/ajax-set-region')
                            ],
                            'pluginOptions' => ['allowClear' => true, 'tags' => true],
                            'pluginEvents' => [
                                'change' => new JsExpression('function() {
                                    $.post( "/district/ajax-set-region/", { district_id: $(this).data("district"), region_ids: $(this).val() })
                                }'),
                            ]
                        ]);
                    }
                ],
                ['class' => 'yii\grid\ActionColumn', 'template' => Yii::$app->RbacActionTemplate::check()],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>