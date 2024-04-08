<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Images;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (!empty ($model->id)): ?>
    <?php
    $url_dragfile = Url::to(['images/ajax-dragfile/']);
    $url_delete = Url::to(['images/ajax-delete/']);

    $initialPreview = [];
    $initialPreviewConfig = [];
    // TODO вынести путь в глобальные переменные
    $path = "/var/www/br_network/frontend/web/img_d/{$model->venue_id}/";

    if (is_dir($path)) {
        $images = Images::find()->where(['room_id' => $model->id])->orderBy(['sort' => SORT_ASC])->all();
        foreach ($images as $image) {
            $initialPreview[] = Html::img($image->subpath, ['class' => 'file-preview-image']);
            $initialPreviewConfig[] = ['caption' => '', 'width' => "120px", 'url' => $url_delete, 'key' => $image->id];
        }
    }
    ?>

    <?= $form->field($model, 'rooms_images[]')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => true],
        'pluginOptions' => [
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
            'overwriteInitial' => false,
        ],
        'pluginEvents' => [
            'filesorted' => 'function(event, params) {
                console.log( params );
                $.ajax({
                    url: "' . $url_dragfile . '",
                    type: "post",
                    data: { previewId: params.stack[params.newIndex].key, oldIndex: params.oldIndex, newIndex: params.newIndex, stack: params.stack},
                }).done(function( log ) {
                    console.log( "Data Saved: " + log );
                });
            }',
        ],
    ]) ?>
<?php endif; ?>