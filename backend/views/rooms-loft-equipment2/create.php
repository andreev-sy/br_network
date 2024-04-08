<?php



/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEquipment2 */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Принадлежности для еды и напитков'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-equipment2-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>