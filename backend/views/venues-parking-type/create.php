<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesParkingType */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Парковка'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-parking-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>