<?php

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftStaff */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Персонал'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-staff-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>