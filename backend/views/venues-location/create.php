<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesLocation */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Расположения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-location-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>