<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Slices;

/**
 * SlicesSearch represents the model behind the search form of `backend\models\Slices`.
 */
class SlicesSearch extends Slices
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'description'], 'integer'],
            [['alias', 'h1', 'title', 'params'], 'safe'],
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
        $query = Slices::find();

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
            'description' => $this->description,
        ]);

        $query->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'h1', $this->h1])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
