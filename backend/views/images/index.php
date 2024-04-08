<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Rooms;
use backend\models\Venues;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Изображения');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header">
        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
    </div>
    <div class="box-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
        
                'id',
                [
                    'attribute' => 'subpath',
                    'format' => 'raw',
                    'value' => function($data){
                        return Html::a(
                            Html::img( $data->subpath, ['class' => 'thumbnail']), 
                            $data->subpath, 
                            ['data-lightbox'=>'roadtrip', 'class'=>'room-image-block']
                        );
                    },
                ],
                [
                    'attribute' => 'venue_id',
                    'value' => 'venue.name',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'venue_id',
                        'data' => Venues::getMap(),
                        'options' => ['placeholder' => Yii::t('app', 'Выберите заведение')],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'tags' => true,
                        ],
                    ])
                ],
                [
                    'attribute' => 'room_id',
                    'value' => 'room.param_name_alt',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'room_id',
                        'data' => Rooms::getMap(),
                        'options' => ['placeholder' => Yii::t('app', 'Выберите зал')],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'tags' => true,
                        ],
                    ])
                ],
                'realpath:ntext',
                'webppath:ntext',
                'waterpath:ntext',
                // 'timestamp:datetime',
                'sort',
        
                // ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>