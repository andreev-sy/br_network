<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FormRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Заявки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-request-index box box-primary">
    <?php Pjax::begin(); ?>

    <div class="box-header">
        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
        
                'id',
                [
                    'attribute'=>'text',
                    'format'=>'raw',
                ],
                [
                    'attribute'=>'text_ru',
                    'format'=>'raw',
                ],
                // 'text_full:ntext',
                'date',
                // 'type',
                [
                    'attribute'=>'utm',
                    'format'=>'raw',
                    'value'=> function($data){
                        return implode('<br>', explode('&', $data->utm));
                    },
                ],
                // 'created_at',
                // 'updated_at',
        
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>