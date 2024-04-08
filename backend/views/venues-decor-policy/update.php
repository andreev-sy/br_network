<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesDecorPolicy */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Правила украшения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-decor-policy-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
