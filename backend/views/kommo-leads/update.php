<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KommoLeads */

$this->title = Yii::t('app', 'Изменение {modelClass}: ', [
    'modelClass' => 'Kommo Leads',
]) . $model->lead_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kommo Leads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lead_id, 'url' => ['view', 'id' => $model->lead_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="kommo-leads-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
