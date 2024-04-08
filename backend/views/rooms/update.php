<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */

$this->title = Yii::t('app', 'Изменение элемента') .' '. $model->param_name_alt;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Залы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->param_name_alt, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="rooms-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
