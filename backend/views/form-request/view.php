<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\FormRequest */

$this->title = '#' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заявки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-request-view box box-primary">
    <div class="box-header">
        <?php 
        if(Yii::$app->user->can('/form-request/delete')){
            echo Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-flat',
                'data' => [
                    'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить элемент?'),
                    'method' => 'post',
                ],
            ]);
        }
        if(Yii::$app->user->can('/collection/create-from-request') or Yii::$app->user->can('/collection/view')){
            if ($model->type == 'client'){
                if (empty($model->collection)){
                    echo Html::a(Yii::t('app','Создать подборку'), ['/collection/create-from-request', 'id'=>$model->id], ['class' => 'btn btn-primary']);
                }else{
                    echo Html::a(Yii::t('app','Подборка'), ['/collection/view', 'id'=>$model->collection->id], ['class' => 'btn btn-success']);
                }
            }
        } 
        ?>
           
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'text',
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'text_ru',
                    'format' => 'raw',
                ],
                'text_full:ntext',
                'date',
                'type',
                [
                    'attribute' => 'utm',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return implode('<br>', explode('&', $data->utm));
                    },
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>