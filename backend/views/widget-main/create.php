<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WidgetMain */

$this->title = 'Create Widget Main';
$this->params['breadcrumbs'][] = ['label' => 'Widget Mains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-main-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
