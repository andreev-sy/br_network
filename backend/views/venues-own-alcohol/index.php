<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Можно свой алкоголь');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-own-alcohol-index box box-primary">
    <?php Pjax::begin(); ?>
    <?php if(Yii::$app->user->can('/venues-own-alcohol/create')): ?>
        <div class="box-header with-border">
            <?= Html::a(Yii::t('app', 'Добавить элемент'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
    <?php endif; ?>

    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
        
                'id',
                'text:ntext',
                'text_ru:ntext',
                ['class' => 'yii\grid\ActionColumn', 'template' => Yii::$app->RbacActionTemplate::check()],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>