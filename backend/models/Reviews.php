<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "Reviews".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $author
 * @property string $date
 * @property string $rating
 * @property integer $room_id
 * @property integer $active
 */

class Reviews extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'reviews';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['text', 'author', 'active'], 'required'],
			[['text', 'title', 'author', 'rating', 'date'], 'string'],
			[['room_id', 'active', 'room_id'], 'integer'],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'title' => 'Title',
			'text' => 'Text',
			'author' => 'Author',
			'date' => 'Date',
			'rating' => 'Rating',
			'room_id' => 'room_id',
			'active' => 'Active',
		];
	}

    public function getRoom(){
        return $this->hasOne(Rooms::className(), ['id' => 'room_id']);
    }

}