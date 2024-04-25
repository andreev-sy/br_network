<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Collection;

/**
 * CollectionSearch represents the model behind the search form of `backend\models\Collection`.
 */
class CollectionSearch extends Collection
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'spec_id', 'guest_id', 'price_person_id', 'contact_type_id', 'city_id', 'form_request_id', 'manager_user_id', 'pool', 'place_barbecue', 'open_area'], 'integer'],
            [['name', 'date', 'phone', 'desire', 'hash', 'created_at', 'updated_at'], 'safe'],
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
        $query = Collection::find();

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
            'collection.id' => $this->id,
            'collection.spec_id' => $this->spec_id,
            'collection.guest_id' => $this->guest_id,
            'collection.price_person_id' => $this->price_person_id,
            'collection.contact_type_id' => $this->contact_type_id,
            'collection.city_id' => $this->city_id,
            'collection.form_request_id' => $this->form_request_id,
            'collection.manager_user_id' => $this->manager_user_id,
            'collection.pool' => $this->pool,
            'collection.place_barbecue' => $this->place_barbecue,
            'collection.open_area' => $this->open_area,
            'collection.created_at' => $this->created_at,
            'collection.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'collection.name', $this->name])
            ->andFilterWhere(['like', 'collection.date', $this->date])
            ->andFilterWhere(['like', 'collection.phone', $this->phone])
            ->andFilterWhere(['like', 'collection.desire', $this->desire])
            ->andFilterWhere(['like', 'collection.hash', $this->hash]);


        $query->joinWith(['formRequest']);
        $query->joinWith(['managerUser']);

        return $dataProvider;
    }
}
