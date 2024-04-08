<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\District;

/**
 * DistrictSearch represents the model behind the search form of `backend\models\District`.
 */
class DistrictSearch extends District
{
    public $region_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'agglomeration_id'], 'integer'],
            [['name', 'region_id'], 'safe'],
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
        $query = District::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // $dataProvider->setSort([
        //     'attributes' => [
        //         'id',
        //         'name',
        //         'city_id' => [
        //             'asc' => ['cities.name' => SORT_ASC],
        //             'desc' => ['cities.name' => SORT_DESC],
        //         ],
        //         'region_id' => [
        //             'asc' => ['region.name' => SORT_ASC],
        //             'desc' => ['region.name' => SORT_DESC],
        //         ],
        //     ]
        // ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            // $query->joinWith(['city']);
            // $query->joinWith(['districtRegionVias.region']);

            return $dataProvider;
        } 
 
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'agglomeration_id' => $this->agglomeration_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        if(!empty($this->region_id)){
            $query->joinWith(['districtRegionVias' => function ($q) {
                $q->where('district_region_via.region_id = "' . $this->region_id . '"');
            }]);
        }
        

        return $dataProvider;
    }
}
