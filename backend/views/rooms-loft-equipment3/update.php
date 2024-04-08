<?php

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipment3 */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Профессиональное оборудование'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="rooms-loft-equipment3-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
