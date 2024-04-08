<?php

/* @var $this yii\web\View */
/* @var $model backend\models\CollectionSpec */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Мероприятия'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-spec-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>