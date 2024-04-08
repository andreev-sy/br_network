<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Venues */

$this->title = Yii::t('app', 'Изменение элемента') .': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заведения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
