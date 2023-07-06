<?php

namespace common\models;

use Yii;
use common\models\siteobject\BaseSiteObject;
use common\models\Rooms;
use common\models\RoomsModule;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $name
 * @property int $restaurant_id
 */
class RoomsModuleSearch extends RoomsModule
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id','gorko_id', 'restaurant_id'], 'integer'],
            [['name'], 'string'],
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
        ];
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
        $query = RoomsModule::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        //echo "<pre 111111111111111111111111>";var_dump($this->validate());echo "</pre>"; exit;

        /*if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }*/

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere([
            'gorko_id' => $this->gorko_id,
        ]);

        $query->andFilterWhere([
            'restaurant_id' => $this->restaurant_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}