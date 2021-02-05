<?php

namespace backend\modules\banketnye_zaly_moskva\controllers;

use common\models\User;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

abstract class BaseBackendController extends Controller
{
    public function beforeAction($action)
    {
        $routesAllowedForGuest = ['site/login','site/reset-password',];

        if (!in_array($action->controller->module->requestedRoute, $routesAllowedForGuest) && \Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        
        return parent::beforeAction($action);
    }
}