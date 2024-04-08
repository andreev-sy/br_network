<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use backend\models\Agglomeration;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Округа');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-index box box-primary">
    <?php Pjax::begin(); ?>
    <?php if (Yii::$app->user->can('/region/create')): ?>
        <div class="box-header with-border">
            <?= Html::a(Yii::t('app', 'Добавить элемент'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
    <?php endif; ?>

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
                                'data-region' => $data->id,
                                'disabled' => !Yii::$app->user->can('/region/ajax-set-agglomeration')
                            ],
                            'pluginEvents' => [
                                'change' => new JsExpression('function() {
                                    $.post( "/region/ajax-set-agglomeration/", { region_id: $(this).data("region"), agglomeration_id: $(this).val() })
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