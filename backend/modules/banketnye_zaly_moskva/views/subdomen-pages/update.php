<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SubdomenPages */

$this->title = 'Правка SEO для страницы ' . $model->page->name . ' в ' . $model->subdomen->name;
$this->params['breadcrumbs'][] = ['label' => 'Subdomen Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subdomen-pages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
