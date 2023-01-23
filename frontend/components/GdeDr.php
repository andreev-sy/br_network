<?php

namespace frontend\components;

use \Yii;

class GdeDr
{
    // Модель элементов фильтра
    public static function getFilterItemsModel($filter_id = '')
    {
        if (!empty($filter_id))
            return array_filter(Yii::$app->params['filter_items_model'], function ($data) use ($filter_id){
                return $data['filter_id'] == $filter_id;
            });

        return Yii::$app->params['filter_items_model'];
    }

    // Модель фильтра
//    public static function getFilterModel()
//    {
//        return Yii::$app->params['filter_model'];
//    }

    // Модель городов
//    public static function getSubdomenRecords()
//    {
//        return Yii::$app->params['activeSubdomenRecords'];
//    }

    // Текущий урл
    public static function getCurrentUrl()
    {
        return '/'.Yii::$app->request->getPathInfo();
    }

    // УРЛ ДЛЯ ЗАЛА
    public static function getRoomUrl($rest, $room)
    {
        $subdomen = Yii::$app->params['subdomen'];
        return "/{$subdomen}katalog/{$rest}/{$room}/";
    }

    // УРЛ ДЛЯ РЕСТОРАНА
    public static function getRestUrl($rest)
    {
        $subdomen = Yii::$app->params['subdomen'];
        return "/{$subdomen}katalog/{$rest}/";
    }

    // ЛЮБОЙ УРЛ
    public static function getUrl($alias = '')
    {
        $subdomen = Yii::$app->params['subdomen'];
        if (empty($alias))
            return "/{$subdomen}";

        return "/{$subdomen}{$alias}/";
    }

    // SUBDOMEN ФОРМАТА "city"
//    public static function getSubdomen()
//    {
//        return Yii::$app->params['subdomen_alias'];
//    }

    // SUBDOMEN ФОРМАТА "city/"
//    public static function getSubdomenUrl()
//    {
//        return Yii::$app->params['subdomen'];
//    }

    // ВОЗВРАЩАЕТ ТЕКУЩИЙ ГОРОД
//    public static function getCity($type='')
//    {
//        switch ($type)
//        {
//            case 'rod':
//                return Yii::$app->params['subdomen_rod'];
//            case 'dec':
//                return Yii::$app->params['subdomen_dec'];
//            default:
//                return Yii::$app->params['subdomen_name'];
//        }
//    }

    public static function getGet()
    {
        return Yii::$app->request->get();
    }

    public static function getPost()
    {
        return Yii::$app->request->post();
    }

    public static function getParams()
    {
        return Yii::$app->params;
    }

    public static function dump($array = [], $die = false)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        
        if ($die == true)
            die();
    }

    public static function vardump($array = [], $die = false)
    {
        echo '<pre>';
        var_dump($array);
        echo '</pre>';

        if ($die == true)
            die();
    }

}