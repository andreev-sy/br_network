<?php

namespace common\models;

use common\models\siteobject\BaseSiteObject;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "yamap_info".
 *
 * @property int $id
 * @property int $gorko_id
 */
class YamapInfo extends BaseSiteObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'yamap_info';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['gorko_id'], 'required'],
			[['gorko_id', ], 'integer'],
			[['name', 'latitude', 'longitude', 'url', 'type_on_map', 'name_on_map', 'awards', 'review_tags', 'site', 'working_hours', 'description', 'features', 'special_menu', 'place_features', 'screens', 'music', 'general_info', 'place_types', 'sport_entertainment', 'sport', 'for_children', 'infrastructure',], 'string'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'gorko_id' => 'Gorko ID',
			'name' => 'Название у нас в БД',
			'latitude' => 'Координаты (Широта)',
			'longitude' => 'Координаты (Долгота)',
			'url' => 'Ссылка на картах',
			'type_on_map' => 'Тип в Яндекс.Картах',
			'name_on_map' => 'Название',
			'awards' => 'Награды',
			'review_tags' => 'Плашки в отзывах с процентами',
			'site' => 'Сайт',
			'working_hours' => 'Время работы (График)',
			'description' => 'Описание ',
			'features' => 'Особенности',
			'special_menu' => 'Специальное меню',
			'place_features' => 'Особенности заведения',
			'screens' => 'Число экранов',
			'music' => 'Музыка',
			'general_info' => 'Общая информация',
			'place_types' => 'Тип заведения',
			'sport_entertainment' => 'Спорт и развлечения',
			'sport' => 'Спорт',
			'for_children' => 'Детям',
			'infrastructure' => 'Инфраструктура',
		];
	}

	public function getYandexReview()
	{
		return $this->hasOne(YandexReview::className(), ['gorko_id' => 'gorko_id']);
	}
}
