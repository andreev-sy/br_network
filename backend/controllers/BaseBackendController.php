<?php

namespace backend\controllers;

use yii\web\Controller;

abstract class BaseBackendController extends Controller
{
    public function beforeAction($action)
    {
        $routesAllowedForGuest = [
            'site/login',
        ];

        if (!in_array($action->controller->module->requestedRoute, $routesAllowedForGuest) && \YII::$app->user->isGuest) {
                return $this->redirect(['site/login']);
        }
        
        return parent::beforeAction($action);
    }
    
}
