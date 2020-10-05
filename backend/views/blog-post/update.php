<?php

use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\blog\BlogPost $model
 */
$this->title = Yii::t('models', 'Blog Post');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Blog Post'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Edit');
?>
<div class="giiant-crud blog-post-update">

    <h1>
        <?php echo Yii::t('models', 'Blog Post') ?>
        <small>
                        <?php echo Html::encode($model->name) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('cruds', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
        'model' => $model,
	]); ?>

</div>
