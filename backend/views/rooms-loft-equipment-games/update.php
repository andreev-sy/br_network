<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipmentGames */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Игры'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="rooms-loft-equipment-games-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
