<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RoomsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rooms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rooms', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'gorko_id',
            'name:ntext',
            'restaurant_id',
            'price',
            //'capacity_reception',
            //'capacity',
            //'type',
            //'type_name:ntext',
            //'rent_only',
            //'banquet_price',
            //'bright_room',
            //'separate_entrance',
            //'features:ntext',
            //'cover_url:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
