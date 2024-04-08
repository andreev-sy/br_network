<?php

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipmentGames */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Игры'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-equipment-games-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>