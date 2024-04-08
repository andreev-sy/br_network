<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipment3 */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Профессиональное оборудование'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-equipment3-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>