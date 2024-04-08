<?php



/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipmentInterior */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Предметы интерьера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-equipment-interior-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>