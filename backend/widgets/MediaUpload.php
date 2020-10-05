<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class MediaUpload extends Widget
{
  public $label = 'Файлы';
  public $multiple = false;
  public $hidden = false;
  public $model;
  public $lastMedia = null;

  public function init()
  {
    parent::init();
  }

  public function run()
  {
    return $this->render('media-upload', [
      'label' => $this->label,
      'hidden' => $this->hidden,
      'multiple' => $this->multiple,
      'model' => $this->model,
      'lastMedia' => $this->lastMedia
    ]);
  }
}
