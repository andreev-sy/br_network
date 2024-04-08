<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesSeatingArrangement */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Расстановка столов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-seating-arrangement-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>