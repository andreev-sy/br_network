<?php


/* @var $this yii\web\View */
/* @var $model backend\models\CollectionContactType */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Типы контакта'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-contact-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>