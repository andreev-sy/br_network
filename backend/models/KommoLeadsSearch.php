<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KommoLeads;

/**
 * KommoLeadsSearch represents the model behind the search form of `backend\models\KommoLeads`.
 */
class KommoLeadsSearch extends KommoLeads
{
    public $filter_year, $filter_month;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lead_id', 'response_time_id', 'is_night', 'status_id', 'rejection_id'], 'integer'],
            [['labor_cost', 'response_time', 'created_at', 'updated_at', 'filter_year', 'filter_month'], 'safe'],
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
        $query = KommoLeads::find();

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

        if(empty($this->filter_year)) $this->filter_year = date('Y');
        if(empty($this->filter_month)) $this->filter_month = date('m');

        $start_date = strtotime($this->filter_year . '-' . $this->filter_month . '-01');
        $end_date = strtotime('+1 month', $start_date);


        // grid filtering conditions
        $query->andFilterWhere([
            'lead_id' => $this->lead_id,
            'labor_cost' => $this->labor_cost,
            'response_time' => $this->response_time,
            'response_time_id' => $this->response_time_id,
            'is_night' => $this->is_night,
            'status_id' => $this->status_id,
            'rejection_id' => $this->rejection_id,
            // 'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]); 

        $query->andFilterWhere(['between', 'created_at', $start_date, $end_date]);

        return $dataProvider;
    }
}
