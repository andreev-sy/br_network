<?php

namespace common\models;

use common\models\siteobject\BaseSiteObject;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "restaurants_yandex".
 *
 * @property int $id
 * @property int $gorko_id
 * @property string $name
 * @property string $address
 */
class RestaurantsYandex extends BaseSiteObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'restaurants_yandex';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['gorko_id', 'city_id'], 'required'],
			[['gorko_id', 'district', 'parent_district', 'city_id', 'commission', 'active', 'rev_ya_id'], 'integer'],
			[['name', 'address', 'latitude', 'longitude', 'phone', 'rev_ya_rate', 'rev_ya_count',], 'string'],
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
			'name' => 'Название ресторана',
			'address' => 'Адрес',
			'rev_ya_id' => 'Отзывы с Яндекса ID',
			'rev_ya_rate' => 'Рейтинг отзывов с Яндекса',
			'rev_ya_count' => 'Количество отзывов на Яндексе',
		];
	}

	public function beforeSave($insert)
	{
		//сохранение рейтинга и количества отзывов с Яндекса
		$url = "https://yandex.ru/maps-reviews-widget/$this->rev_ya_id?comments";
		$ci = curl_init($url);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ci);

		$yandex_rate = '';
		$yandex_count = '';
		//парсинг рейтинга с Яндекса
		preg_match_all('/<p[^>]+?class="mini-badge__stars-count">(.+?)<\/p>/su', $response, $matches_rate);
		if (isset($matches_rate[1][0])) {
			$yandex_rate = str_replace(',', '.', $matches_rate[1][0]);
		}

		//парсинг количества отзывов с Яндекса
		/* preg_match_all('/<a[^>]+?class="mini-badge__rating"[^>]+?>[^>]*?(.+?) отз[^>]+?<\/a>/su', $response, $matches_count); */
		preg_match_all('/<a[^>]+?class="mini-badge__rating"[^>]+?>[^>]*?(.+?) •[^>]*?<\/a>/su', $response, $matches_count);
		if (isset($matches_count[1][0])) {
			$yandex_count = $matches_count[1][0];
		}

		$this->rev_ya_rate = $yandex_rate;
		$this->rev_ya_count = $yandex_count;

		return parent::beforeSave($insert);
	}
}
