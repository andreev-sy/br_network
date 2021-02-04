<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'kartik\file\SortableAsset',
        'kartik\file\FileInputAsset',
    ];

    public function init() {
        parent::init();
        $this->css = $this->getVersionedFiles($this->css);
        $this->js = $this->getVersionedFiles($this->js);
    }

    public function getVersionedFiles($files)
    {
        $out = [];
        foreach ($files as $file) {
            $filePath = \Yii::getAlias('@webroot/' . $file);
            $out[] = $file . (is_file($filePath) ? '?v=' . filemtime($filePath) : '');
        }
        return $out;
    }
}
