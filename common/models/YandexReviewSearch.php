<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * YandexReviewSearch represents the model behind the search form of `common\models\YandexReview`.
 */
class YandexReviewSearch extends Restaurants
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['gorko_id'], 'integer'],
			[['name', 'address'], 'string'],
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
		$query = Restaurants::find()->with('yandexReview');

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
