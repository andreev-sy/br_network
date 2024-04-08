<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftColor */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Цвета'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="rooms-loft-color-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
