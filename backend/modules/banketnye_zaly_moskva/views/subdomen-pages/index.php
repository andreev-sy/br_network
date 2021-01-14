<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubdomenPagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subdomen Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subdomen-pages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Subdomen Pages', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title:ntext',
            'description:ntext',
            'keywords:ntext',
            'img_alt:ntext',
            //'h1:ntext',
            //'text_top:ntext',
            //'text_bottom:ntext',
            //'title_pag:ntext',
            //'description_pag:ntext',
            //'keywords_pag:ntext',
            //'h1_pag:ntext',
            //'page_id',
            //'subdomen_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
