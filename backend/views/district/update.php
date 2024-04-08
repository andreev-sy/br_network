<?php

/* @var $this yii\web\View */
/* @var $model backend\models\District */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Районы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="district-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
