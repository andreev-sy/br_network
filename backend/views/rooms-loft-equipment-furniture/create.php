<?php



/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipmentFurniture */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Мебель'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-equipment-furniture-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>