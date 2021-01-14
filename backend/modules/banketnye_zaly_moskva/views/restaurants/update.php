<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Restaurants */

$this->title = 'Редактирование ресторана: ' . $rest_model->name;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $rest_model->name, 'url' => ['view', 'id' => $rest_model->id, 'gorko_id' => $rest_model->gorko_id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="restaurants-update">

    <?= $this->render('_form', [
        'model' => $model,
        'rest_model' => $rest_model
    ]) ?>

</div>
