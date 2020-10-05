<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\blog;

use common\models\siteobject\BaseMediaEnum;
use Mustache_Engine;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the base-model class for table "blog_block".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $template
 * @property string $inputs
 * @property string $type
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property \common\models\User $createdBy
 * @property \common\models\User $updatedBy
 * @property \common\models\blog\BlogPostBlock[] $blogPostBlocks
 * @property string $aliasModel
 */
class BlogBlock extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog_block';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['template', 'inputs', 'type'], 'string'],
            [['inputs'], 'inputsValidate'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['updated_by' => 'id']]
        ];
    }

    public function extraFields()
    {
        return ['blockTypeLabel' => function ($model) {
            return BlockTypeEnum::getLabel($model->type);
        }];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'name' => Yii::t('models', 'Name'),
            'alias' => Yii::t('models', 'Alias'),
            'template' => Yii::t('models', 'Template'),
            'inputs' => Yii::t('models', 'Inputs'),
            'type' => Yii::t('models', 'Type'),
            'created_by' => Yii::t('models', 'Created By'),
            'created_at' => Yii::t('models', 'Created At'),
            'updated_by' => Yii::t('models', 'Updated By'),
            'updated_at' => Yii::t('models', 'Updated At'),
        ];
    }

    public function inputsValidate($attribute, $params, $validator)
    {
        try {
            Json::decode($this->inputs);
        } catch (\Throwable $th) {
            $this->addError('inputs', 'Invalid json:' . $th->getMessage());
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogPostBlocks()
    {
        return $this->hasMany(\common\models\blog\BlogPostBlock::className(), ['blog_block_id' => 'id']);
    }

    /** @param BlogPostBlock $blogPostBlock */
    public function render($blogPostBlock)
    {
        $data = [];
        try {
            $dataFromInputs = Json::decode($blogPostBlock->content);
            $inputs = Json::decode($this->inputs);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        if (!empty($dataFromInputs)) {
            $data = ArrayHelper::merge($data, $dataFromInputs);
        }

        foreach ($inputs as $inputType => $inputsMeta) {
            if (in_array($inputType, [BaseMediaEnum::IMAGE])) {
                $dataFromFileInputs =  array_reduce($inputsMeta, function ($acc, $inputMeta) use ($inputType, $blogPostBlock) {
                    $mediaTargetType = $inputType . '_' . $inputMeta['slug'];
                    $acc[$mediaTargetType] = array_map(
                        function ($fileData) {
                            return [
                                'src' => $fileData->src,
                                'alt' => $fileData->alt,
                            ];
                        },
                        $blogPostBlock->getFilesData($mediaTargetType)
                    );
                    return $acc;
                }, []);
                $data = ArrayHelper::merge($data, $dataFromFileInputs);
            }
        }

        $mustache = new Mustache_Engine([
            'escape' => function ($text) {
                return $text;
            },
        ]);

        return $mustache->render($this->template, $data);
    }

}