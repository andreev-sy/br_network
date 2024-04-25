<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\KommoLeadsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kommo Leads');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kommo-leads-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Добавить Kommo Leads'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-header">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="box-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
        
                'lead_id',
                'labor_cost',
                'response_time',
                'response_time_id',
                'is_night',
                'status_id',
                'rejection_id',
                'created_at',
                'updated_at',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>