<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesStatus */

$this->title = Yii::t('app', 'Изменение {modelClass}: ', [
    'modelClass' => 'Venues Status',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Venues Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-status-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
