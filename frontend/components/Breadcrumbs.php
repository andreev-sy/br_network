<?php

namespace frontend\components;

use Yii;

class Breadcrumbs {
    public static function get_breadcrumbs($level) {
        $subdomen = Yii::$app->params['subdomen'];
        switch ($level) {
            case 1:
                $breadcrumbs=[
                    '/'.$subdomen => 'Свадьба на природе',
                ];
                break;
            case 2:
                $breadcrumbs=[
                    '/'.$subdomen => 'Свадьба на природе',
                    "/{$subdomen}catalog/" => 'Банкетные залы',
                ];
                break;
        }
        return $breadcrumbs;
    }
}