<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLocation */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Расположение'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-location-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>