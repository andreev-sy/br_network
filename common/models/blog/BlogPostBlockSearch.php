<?php
/**
 * /var/www/liderpoiska_v2/console/runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace common\models\blog;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlogPostBlock represents the model behind the search form about `common\models\blog\BlogPostBlock`.
 */
class BlogPostBlockSearch extends BlogPostBlock
{

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function rules() {
		return [
			[['id', 'blog_post_id', 'blog_block_id', 'sort'], 'integer'],
			[['content'], 'safe'],
		];
	}


	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}


	/**
	 * Creates data provider instance with search query applied
	 *
	 *
	 * @param array   $params
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = BlogPostBlock::find()->orderBy(['sort' => SORT_ASC]);

		$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => false,
			]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere([
				'id' => $this->id,
				'blog_post_id' => $this->blog_post_id,
				'blog_block_id' => $this->blog_block_id,
				'sort' => $this->sort,
			]);

		$query->andFilterWhere(['like', 'content', $this->content]);

		return $dataProvider;
	}


}
