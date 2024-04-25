<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftStaff */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Персонал'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="rooms-loft-staff-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>