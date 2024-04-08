<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\Message */

$this->title = Yii::t('app', 'Импорт переводов');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Переводы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">

    <div class="message-form box box-primary">
        <form action="<?= Url::to(['message/import']) ?>" method="post">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

            <div class="box-body table-responsive">
                <?= Html::textarea('data', !empty($_POST['data']) ? $_POST['data'] : '', ['style' => 'width: 100%;', 'rows' => 30]) ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-success btn-flat']) ?>
            </div>
        </form>
    </div>

</div>