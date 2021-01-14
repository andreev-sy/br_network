<?php

namespace app\modules\banketnye_zaly_moskva\controllers;

use yii\web\Controller;

/**
 * Default controller for the `banketnye_zaly_moskva` module
 */
class DefaultController extends Controller
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
