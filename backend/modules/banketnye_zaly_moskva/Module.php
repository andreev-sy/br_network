<?php

namespace backend\modules\banketnye_zaly_moskva;
use Yii;
/**
 * svadbanaprirode module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\banketnye_zaly_moskva\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::$app->params['uploadFolder'] = 'upload';

        parent::init();

        // custom initialization code goes here
    }
}
