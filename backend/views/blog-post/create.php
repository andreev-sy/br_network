<?php

use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\blog\BlogPost $model
 */
$this->title = Yii::t('models', 'Blog Post');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Blog Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud blog-post-create">

    <h1>
        <?php echo Yii::t('models', 'Blog Post') ?>
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