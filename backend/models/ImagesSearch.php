<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Images;

/**
 * ImagesSearch represents the model behind the search form of `backend\models\Images`.
 */
class ImagesSearch extends Images
{
    public $rooms_images;
    public $venues_images;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'venue_id', 'room_id', 'timestamp', 'sort'], 'integer'],
            [['realpath', 'subpath', 'webppath', 'waterpath', 'rooms_images', 'venues_images'], 'safe'],
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
        $query = Images::find();

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
            'room_id' => $this->room_id,
            'timestamp' => $this->timestamp,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'realpath', $this->realpath])
            ->andFilterWhere(['like', 'subpath', $this->subpath])
            ->andFilterWhere(['like', 'webppath', $this->webppath])
            ->andFilterWhere(['like', 'waterpath', $this->waterpath]);

        if(!empty($this->venues_images)){
            $query->andWhere(['not', ['venue_id' => null]]);
        }
        
        if(!empty($this->rooms_images)){
            $query->andWhere(['not', ['room_id' => null]]);
        }

        return $dataProvider;
    }
}
