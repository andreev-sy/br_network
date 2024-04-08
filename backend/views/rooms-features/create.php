<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsFeatures */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Особенности'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-features-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>