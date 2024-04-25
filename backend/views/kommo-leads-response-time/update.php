<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KommoLeadsResponseTime */

$this->title = Yii::t('app', 'Изменение {modelClass}: ', [
    'modelClass' => 'Kommo Leads Response Time',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kommo Leads Response Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="kommo-leads-response-time-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
