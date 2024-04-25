<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\KommoLeads */

$this->title = $model->lead_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kommo Leads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kommo-leads-view box box-primary">
    <div class="box-header">
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->lead_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->lead_id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить элемент?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'lead_id',
                'labor_cost',
                'response_time',
                'response_time_id:datetime',
                'is_night',
                'status_id',
                'rejection_id',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
