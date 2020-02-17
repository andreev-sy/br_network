<?php

namespace frontend\components;

use yii\base\BaseObject;
use Yii;
use yii\imagine\Image;

class AsyncDownloads extends BaseObject implements \yii\queue\JobInterface
{
    public $url;
    public $file;
    
    public function execute($queue)
    {
        $size_origin = getimagesize($this->url);
		$size_watermark = getimagesize('/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png');
		$start_point = [$size_origin[0] - $size_watermark[0] - 20, $size_origin[1] - $size_watermark[1] - 10];
		$image = Image::watermark($this->url, '/var/www/pmnetwork/pmnetwork/frontend/web/img/watermark.png', $start_point)->save($this->file, ['quality' => 100]);
    }
}