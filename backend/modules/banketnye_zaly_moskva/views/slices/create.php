<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Slices */

$this->title = 'Create Slices';
$this->params['breadcrumbs'][] = ['label' => 'Slices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
