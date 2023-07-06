<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property string $text
 * @property string $title
 * @property string $author
 * @property string $date
 * @property string $rating
 * @property int $restaurant_id
 * @property int $active

 */
class Reviews extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'reviews';
    }

    public function rules()
    {
        return [
            [['text', 'author','active'], 'required'],
            [['restaurant_id','active'], 'integer'],
            [['text', 'author', 'date', 'title', 'text', 'rating'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'author' => 'Author',
            'date' => 'Date',
            'rating' => 'Rating',
            'restaurant_id' => 'Restaurant ID',
            'active' => 'Active',
        ];
    }

    public function getRestaurants()
    {
        return $this->hasOne(Restaurants::className(), ['id' => 'restaurant_id']);
    }

}
