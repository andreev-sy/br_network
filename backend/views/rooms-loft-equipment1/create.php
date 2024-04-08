<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipment1 */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Техника и другое оборудование'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-equipment1-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>