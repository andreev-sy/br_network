<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use backend\models\ViaHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', 'Подборки');
$this->params['breadcrumbs'][] = $this->title;

$action_template[] = Yii::$app->user->can('/collection/view') ? '{view}' : '';
$action_template[] = Yii::$app->user->can('/collection/update') ? '{update}' : '';
$action_template[] = Yii::$app->user->can('/collection/delete') ? '{delete}' : '';
?>
<div class="collection-index box box-primary">
    <?php Pjax::begin(); ?>

    <?php if(Yii::$app->user->can('/collection/create')): ?>
        <div class="box-header with-border">
            <?= Html::a(Yii::t('app', 'Добавить элемент'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
    <?php endif; ?>

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
                'name',
                'date',
                'phone',
                [
                    'attribute'=>'spec_id',
                    'value'=> function($data){
                        return ViaHelper::getText($data->spec);
                    },
                ],
                [
                    'attribute'=>'guest_id',
                    'value'=> function($data){
                        return ViaHelper::getText($data->guest);
                    },
                ],
                [
                    'attribute'=>'price_person_id',
                    'value'=> function($data){
                        return ViaHelper::getText($data->pricePerson);
                    },
                ],
                // [
                //     'attribute'=>'contact_type_id',
                //     'value'=> function($data){
                //         return ViaHelper::getText($data->contactType);
                //     },
                // ],
                // 'city_id',
                // 'desire:ntext',
                [
                    'attribute'=>'form_request_id',
                    'format'=>'raw',
                    'value'=> function($data){
                        return $data->form_request_id ? Html::a($data->formRequest->name, ['form-request/view','id'=>$data->form_request_id], ['target'=>'_blank']) : null;
                    }
                ], 
                [
                    'attribute'=>'manager_user_id',
                    'format'=>'raw',
                    'value'=> function($data){
                        return $data->manager_user_id ? Html::a($data->managerUser->fullname, ['user/view','id'=>$data->manager_user_id], ['target'=>'_blank']) : null;
                    }
                ], 
                // 'pool',
                // 'place_barbecue',
                // 'open_area',
                [
                    'attribute'=>'hash',
                    'format'=>'raw',
                    'value'=> function($data){
                        return Html::a($data->hash, Yii::$app->params['4u_domain'].$data->hash.'/', ['target'=>'_blank']);
                    }
                ], 
                // 'created_at',
                // 'updated_at',
        
                ['class' => 'yii\grid\ActionColumn', 'template' => Yii::$app->RbacActionTemplate::check()],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>