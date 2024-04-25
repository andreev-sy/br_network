<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KommoLeads */

$this->title = Yii::t('app', 'Создание Kommo Leads');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kommo Leads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kommo-leads-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
