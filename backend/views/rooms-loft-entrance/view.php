<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEntrance */

$this->title = '#'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Входы и выходы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-entrance-view box box-primary">
    <?php if(Yii::$app->user->can('/rooms-loft-entrance/update') or Yii::$app->user->can('/rooms-loft-entrance/delete')): ?>
        <div class="box-header">
            <?php if(Yii::$app->user->can('/rooms-loft-entrance/update')): ?>
                <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?php endif; ?>
            <?php if(Yii::$app->user->can('/rooms-loft-entrance/delete')): ?>
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
                'text:ntext',
                'text_ru:ntext',
            ],
        ]) ?>
    </div>
</div>
