<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesVisit */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Визиты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-visit-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>