<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WidgetMain;

/**
 * WidgetMainSearch represents the model behind the search form of `backend\models\WidgetMain`.
 */
class WidgetMainSearch extends WidgetMain
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'slice_id'], 'integer'],
            [['title', 'subtitle', 'text', 'link_text'], 'safe'],
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
        $query = WidgetMain::find();

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
            'slice_id' => $this->slice_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'link_text', $this->link_text]);

        return $dataProvider;
    }
}
