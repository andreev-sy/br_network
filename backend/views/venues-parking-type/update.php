<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesParkingType */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Парковка'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-parking-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
