<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RestaurantsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рестораны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurants-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'gorko_id',
            'name:ntext',
            'address:ntext',
            //'min_capacity',
            //'max_capacity',
            //'price',
            //'cover_url:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
