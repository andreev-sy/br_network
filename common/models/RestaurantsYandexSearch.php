<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantsYandex;

/**
 * RestaurantsYandexSearch represents the model behind the search form of `common\models\RestaurantsYandex`.
 */
class RestaurantsYandexSearch extends RestaurantsYandex
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['gorko_id', 'district', 'parent_district', 'city_id', 'commission', 'active', 'rev_ya_id'], 'integer'],
			[['name', 'address', 'latitude', 'longitude', 'phone', 'rev_ya_rate', 'rev_ya_count',], 'string'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = RestaurantsYandexSearch::find();

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'active' => 1,
			'commission' => 2,
		]);

		$query->andFilterWhere(['like', 'gorko_id', $this->gorko_id])
			->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'address', $this->address]);

		return $dataProvider;
	}
}
