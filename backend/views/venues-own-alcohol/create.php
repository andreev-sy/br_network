<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesOwnAlcohol */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Можно свой алкоголь'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-own-alcohol-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>