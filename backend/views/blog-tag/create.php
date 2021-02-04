<?php

use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\blog\BlogTag $model
 */
$this->title = Yii::t('models', 'Blog Tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Blog Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud blog-tag-create">

    <h1>
        <?php echo Yii::t('models', 'Blog Tag') ?>
        <small>
            <?php echo Html::encode($model->name) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo             Html::a(
                Yii::t('cruds', 'Cancel'),
                \yii\helpers\Url::previous(),
                ['class' => 'btn btn-default']
            ) ?>
        </div>
    </div>

    <hr />

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>