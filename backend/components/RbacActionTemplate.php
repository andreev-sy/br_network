<?php

namespace backend\components;

use Yii;

class RbacActionTemplate
{
    public static function check($controller = null, $template = '{view} {update} {delete}') {
        if(empty($controller)) $controller = Yii::$app->controller->id;
        $result = [];
        $template = explode(' ', $template);
        foreach($template as $temp){
            $action = ltrim($temp, '{');
            $action = rtrim($action, '}');
            if(Yii::$app->user->can("/$controller/$action")){
                $result[] = $temp;
            }
        }

        return implode(' ', $result);
    }
}