<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\VenuesVisit;

/**
 * VenuesVisitSearch represents the model behind the search form of `backend\models\VenuesVisit`.
 */
class VenuesVisitSearch extends VenuesVisit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'venue_id', 'status_id', 'count_banquets'], 'integer'],
            [['person', 'phone', 'phone_wa', 'amount_commission', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = VenuesVisit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'venue_id' => $this->venue_id,
            'status_id' => $this->status_id,
            'count_banquets' => $this->count_banquets,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'person', $this->person])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'phone_wa', $this->phone_wa])
            ->andFilterWhere(['like', 'amount_commission', $this->amount_commission]);

        return $dataProvider;
    }
}
