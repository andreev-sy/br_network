<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kommo Leads Response Times');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kommo-leads-response-time-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Добавить Kommo Leads Response Time'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

        <div class="box-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                'id',
                'text:ntext',
                'text_ru:ntext',
                'min',
                'max',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
