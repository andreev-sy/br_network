<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesDecorPolicy */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Правила украшения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-decor-policy-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>