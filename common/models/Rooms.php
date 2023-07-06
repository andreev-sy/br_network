<?php

namespace common\models;

use common\models\RestaurantsSpec;

use Yii;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $gorko_id
 * @property int $name
 * @property int $restaurant_id
 * @property int $price
 * @property int $min_capacity
 * @property int $max_capacity
 * @property int $type
 * @property string $type_name
 */
class Rooms extends \yii\db\ActiveRecord
{

	public $admin_flag = false;

	public static function tableName()
	{
		return 'rooms';
	}

	public function rules()
	{
		return [
			[['gorko_id', 'name'], 'required'],
			[['gorko_id', 'restaurant_id', 'price', 'capacity_reception', 'capacity', 'capacity_min', 'type', 'rent_only', 'banquet_price','banquet_price_min','rent_room_only', 'banquet_price_person', 'bright_room', 'separate_entrance', 'active', 'in_elastic', 'payment_model'], 'integer'],
			[['type_name', 'name', 'features', 'cover_url', 'description', 'graduation_category'], 'string'],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'gorko_id' => 'Gorko ID',
			'name' => 'Name',
			'restaurant_id' => 'Restaurant ID',
			'price' => 'Price',
			'min_capacity' => 'Min Capacity',
			'max_capacity' => 'Max Capacity',
			'type' => 'Type',
			'type_name' => 'Type Name',
		];
	}

	public function getRestaurants()
	{
		return $this->hasOne(Restaurants::className(), ['gorko_id' => 'restaurant_id']);
	}

	public function getImages()
	{
		return $this->hasMany(Images::className(), ['item_id' => 'gorko_id'])->where(['type' => 'rooms']);
	}

	public function getSpecs()
	{
		//связь строится таким образом RestaurantsSpec::className(), ['id' => 'spec_id'] - 'id' это имя столбца в таблице RestaurantsSpec(где указаны названия типов мероприятий), 'spec_id' это имя столбца в промежуточной таблице 'rooms_restaurants_spec'
		//viaTable('rooms_restaurants_spec', ['gorko_id' => 'gorko_id']) - 'gorko_id' это имя столбца в промежуточной таблице ('rooms_restaurants_spec'), по которой происходит связь с основной таблицей пансионатов ('rooms'), 'gorko_id' это имя столбца в основной таблице ('rooms')
		return $this->hasMany(RestaurantsSpec::className(), ['id' => 'spec_id'])
			->viaTable('rooms_restaurants_spec', ['gorko_id' => 'gorko_id']);
	}
}
