<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KommoLeadsResponseTime */

$this->title = Yii::t('app', 'Создание Kommo Leads Response Time');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kommo Leads Response Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kommo-leads-response-time-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
