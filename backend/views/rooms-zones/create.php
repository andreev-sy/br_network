<?php

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsZones */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Функциональные зоны'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-zones-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>