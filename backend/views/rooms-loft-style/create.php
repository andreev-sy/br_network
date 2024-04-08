<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftStyle */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Стили'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-style-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>