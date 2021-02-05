<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SubdomenPages */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Subdomen Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="subdomen-pages-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:ntext',
            'description:ntext',
            'keywords:ntext',
            'img_alt:ntext',
            'h1:ntext',
            'text_top:ntext',
            'text_bottom:ntext',
            'title_pag:ntext',
            'description_pag:ntext',
            'keywords_pag:ntext',
            'h1_pag:ntext',
            'page_id',
            'subdomen_id',
        ],
    ]) ?>

</div>
