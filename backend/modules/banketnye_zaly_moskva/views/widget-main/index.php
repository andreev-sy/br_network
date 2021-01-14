<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WidgetMainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Widget Mains';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-main-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Widget Main', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'slice_id',
            'title:ntext',
            'subtitle:ntext',
            'text:ntext',
            //'link_text:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
