<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VenuesVisitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Визиты');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-visit-index box box-primary">
    <?php Pjax::begin(); ?>
    <?php if(Yii::$app->user->can('/venues-visit/create')): ?>
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
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
        
                'id',
                'venue_id',
                'person',
                'phone',
                'phone_wa',
                // 'status_id',
                // 'count_banquets',
                // 'amount_commission:ntext',
                // 'created_at',
                // 'updated_at',
                ['class' => 'yii\grid\ActionColumn', 'template' => Yii::$app->RbacActionTemplate::check()],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>