<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SubdomenPages;

/**
 * SubdomenPagesSearch represents the model behind the search form of `common\models\SubdomenPages`.
 */
class SubdomenPagesSearch extends SubdomenPages
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'page_id', 'subdomen_id'], 'integer'],
            [['title', 'description', 'keywords', 'img_alt', 'h1', 'text_top', 'text_bottom', 'title_pag', 'description_pag', 'keywords_pag', 'h1_pag'], 'safe'],
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
        $query = SubdomenPages::find();

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
            'page_id' => $this->page_id,
            'subdomen_id' => $this->subdomen_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'img_alt', $this->img_alt])
            ->andFilterWhere(['like', 'h1', $this->h1])
            ->andFilterWhere(['like', 'text_top', $this->text_top])
            ->andFilterWhere(['like', 'text_bottom', $this->text_bottom])
            ->andFilterWhere(['like', 'title_pag', $this->title_pag])
            ->andFilterWhere(['like', 'description_pag', $this->description_pag])
            ->andFilterWhere(['like', 'keywords_pag', $this->keywords_pag])
            ->andFilterWhere(['like', 'h1_pag', $this->h1_pag]);

        return $dataProvider;
    }
}
