<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Agglomeration */

$this->title = '#' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Агломерации'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agglomeration-view box box-primary">
    <?php if (Yii::$app->user->can('/agglomeration/update') or Yii::$app->user->can('/agglomeration/delete')): ?>
        <div class="box-header">
            <?php if (Yii::$app->user->can('/agglomeration/update')): ?>
                <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('/agglomeration/delete')): ?>
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
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name:ntext',
            ],
        ]) ?>
    </div>
</div>