<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesSites */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сайты источники'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-sites-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
