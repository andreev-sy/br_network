<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesSpec */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Мероприятие в заведении'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-spec-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>