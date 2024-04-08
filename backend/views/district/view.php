<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\District */

$this->title = '#'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Районы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-view box box-primary">
    <?php if(Yii::$app->user->can('/district/update') or Yii::$app->user->can('/district/delete')): ?>
        <div class="box-header">
            <?php if(Yii::$app->user->can('/district/update')): ?>
                <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?php endif; ?>
            <?php if(Yii::$app->user->can('/district/delete')): ?>
                <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-flat',
                    'data' => [
                        'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить элемент?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name:ntext',
                [
                    'attribute'=>'agglomeration_id',
                    'value'=>$model->agglomeration->name,
                ],
                [
                    'attribute'=>'region_id',
                    'value'=>function() use($model){
                        return implode(', ', ArrayHelper::map($model->districtRegionVias, 'region_id', function($data){
                            return $data->region->name;
                        }));
                    },
                ],
            ],
        ]) ?>
    </div>
</div>
