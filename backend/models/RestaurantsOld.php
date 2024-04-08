<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurant_common".
 *
 * 
 * @property string|null $options
 * @property string|null $cuisines
 * @property string|null $specials
 * @property string|null $extra
 * @property string|null $type
 * @property string|null $subtypes
 * @property string|null $category
 * @property string|null $phone_g
 * @property string|null $street
 * @property string|null $working_hours
 * @property string|null $popular_times
 * @property string|null $about
 * @property string|null $range
 * @property string|null $menu_link
 */
class RestaurantsOld extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banket-brazil.restaurant_common';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'options' => Yii::t('app', 'Опции'),
            'cuisines' => Yii::t('app', 'Кухня'),
            'specials' => Yii::t('app', 'Особенности'),
            'extra' => Yii::t('app', 'За дополнительную плату'),
            'type' => Yii::t('app', 'Тип'),
            'subtypes' => Yii::t('app', 'Подтипы'),
            'category' => Yii::t('app', 'Категория'),
            'phone_g' => Yii::t('app', 'Телефон из гугл карт'),
            'street' => Yii::t('app', 'Улица'),
            'working_hours' => Yii::t('app', 'Рабочие часы'),
            'popular_times' => Yii::t('app', 'Популярные время'),
            'about' => Yii::t('app', 'Описание'),
            'range' => Yii::t('app', 'Диапазон'),
            'menu_link' => Yii::t('app', 'Ссылка на меню'),
        ];
    }

}
