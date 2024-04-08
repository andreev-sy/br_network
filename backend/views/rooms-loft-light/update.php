<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftLight */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Освещение'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="rooms-loft-light-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
