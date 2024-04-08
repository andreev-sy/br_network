<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipment2 */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Принадлежности для еды и напитков'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="rooms-loft-equipment2-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
