<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftColor */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Цвета'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-color-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>