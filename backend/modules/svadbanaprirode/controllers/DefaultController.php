<?php

namespace app\modules\svadbanaprirode\controllers;

use backend\controllers\BaseBackendController;
use yii\web\Controller;

/**
 * Default controller for the `svadbanaprirode` module
 */
class DefaultController extends BaseBackendController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
