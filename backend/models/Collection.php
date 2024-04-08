<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "collection".
 *
 * @property int $id
 * @property string $name Имя клиента
 * @property string|null $date Дата
 * @property string $phone Телефон клиента
 * @property int $spec_id Событие
 * @property int $guest_id Количество гостей
 * @property int $price_person_id Стоимость на человека
 * @property int $contact_type_id Тип связи с клиентом
 * @property int $agglomeration_id Агломерация
 * @property int $city_id Город
 * @property string|null $desire Пожелания клиента
 * @property int|null $form_request_id Заявка
 * @property int|null $manager_user_id Менеджер
 * @property int $pool Есть бассейн
 * @property int $place_barbecue Есть место для шашлыка
 * @property int $open_area Есть открытая зона
 * @property string|null $hash Ссылка
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 *
 * @property Cities $city
 * @property Agglomeration $agglomeration
 * @property FormRequest $formRequest
 * @property User $managerUser
 * @property CollectionSpec $spec
 * @property CollectionContactType $contactType
 * @property CollectionGuest $guest
 * @property CollectionPricePerson $pricePerson
 * @property CollectionDistrictVia[] $collectionDistrictVias
 * @property CollectionRegionVia[] $collectionRegionVias
 * @property CollectionVenueVia[] $collectionVenueVias
 */
class Collection extends \yii\db\ActiveRecord
{

    public $is_form;
    public $region_ids;
    public $district_ids;
    public $collection_venue_via;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'collection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'spec_id', 'guest_id', 'price_person_id', 'contact_type_id', 'city_id', 'agglomeration_id'], 'required'],
            [['spec_id', 'guest_id', 'price_person_id', 'contact_type_id', 'city_id', 'agglomeration_id', 'form_request_id', 'manager_user_id', 'pool', 'place_barbecue', 'open_area'], 'integer'],
            [['desire', 'hash'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['date', 'phone'], 'string', 'max' => 50],
            [['agglomeration_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agglomeration::className(), 'targetAttribute' => ['agglomeration_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['form_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormRequest::className(), 'targetAttribute' => ['form_request_id' => 'id']],
            [['manager_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['manager_user_id' => 'id']],
            [['spec_id'], 'exist', 'skipOnError' => true, 'targetClass' => CollectionSpec::className(), 'targetAttribute' => ['spec_id' => 'id']],
            [['contact_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CollectionContactType::className(), 'targetAttribute' => ['contact_type_id' => 'id']],
            [['guest_id'], 'exist', 'skipOnError' => true, 'targetClass' => CollectionGuest::className(), 'targetAttribute' => ['guest_id' => 'id']],
            [['price_person_id'], 'exist', 'skipOnError' => true, 'targetClass' => CollectionPricePerson::className(), 'targetAttribute' => ['price_person_id' => 'id']],
            [['created_at', 'updated_at', 'collection_venue_via', 'region_ids', 'district_ids', 'is_form'], 'safe'],
        ];
    } 

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Имя клиента'),
            'date' => Yii::t('app', 'Дата'),
            'phone' => Yii::t('app', 'Телефон клиента'),
            'spec_id' => Yii::t('app', 'Мероприятие'),
            'guest_id' => Yii::t('app', 'Количество гостей'),
            'price_person_id' => Yii::t('app', 'Стоимость на человека'),
            'contact_type_id' => Yii::t('app', 'Тип связи с клиентом'),
            'agglomeration_id' => Yii::t('app', 'Агломерация'),
            'city_id' => Yii::t('app', 'Город'),
            'region_ids' => Yii::t('app', 'Округ'),
            'district_ids' => Yii::t('app', 'Район'),
            'desire' => Yii::t('app', 'Пожелания клиента'),
            'form_request_id' => Yii::t('app', 'Заявка'),
            'manager_user_id' => Yii::t('app', 'Менеджер'),
            'pool' => Yii::t('app', 'Есть бассейн'),
            'place_barbecue' => Yii::t('app', 'Есть место для шашлыка'),
            'open_area' => Yii::t('app', 'Есть открытая зона'),
            'collection_venue_via' => Yii::t('app', 'Заведения'),
            'hash' => Yii::t('app', 'Ссылка'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * Gets query for [[Agglomeration]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgglomeration()
    {
        return $this->hasOne(Agglomeration::className(), ['id' => 'agglomeration_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[FormRequest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormRequest()
    {
        return $this->hasOne(FormRequest::className(), ['id' => 'form_request_id']);
    }

    /**
     * Gets query for [[ManagerUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManagerUser()
    {
        return $this->hasOne(User::className(), ['id' => 'manager_user_id']);
    }

    /**
     * Gets query for [[Spec]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpec()
    {
        return $this->hasOne(CollectionSpec::className(), ['id' => 'spec_id']);
    }

    /**
     * Gets query for [[ContactType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContactType()
    {
        return $this->hasOne(CollectionContactType::className(), ['id' => 'contact_type_id']);
    }

    /**
     * Gets query for [[Guest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGuest()
    {
        return $this->hasOne(CollectionGuest::className(), ['id' => 'guest_id']);
    }

    /**
     * Gets query for [[PricePerson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPricePerson()
    {
        return $this->hasOne(CollectionPricePerson::className(), ['id' => 'price_person_id']);
    }

    /**
     * Gets query for [[CollectionDistrictVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionDistrictVias()
    {
        return $this->hasMany(CollectionDistrictVia::className(), ['collection_id' => 'id']);
    }

    /**
     * Gets query for [[CollectionRegionVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionRegionVias()
    {
        return $this->hasMany(CollectionRegionVia::className(), ['collection_id' => 'id']);
    }

    /**
     * Gets query for [[CollectionVenueVias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionVenueVias()
    {
        return $this->hasMany(CollectionVenueVia::className(), ['collection_id' => 'id']);
    }



    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        CollectionVenueVia::deleteAll(['collection_id' => $this->id]);
        CollectionRegionVia::deleteAll(['collection_id' => $this->id]);
        CollectionDistrictVia::deleteAll(['collection_id' => $this->id]);

        return true;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->hash = md5($this->name . time());
            }

            return true;
        }

        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->is_form) {
            CollectionRegionVia::deleteAll([ 'collection_id' => $this->id ]);

            if(!empty($this->region_ids)){
                foreach ($this->region_ids as $via_id) {
                    $via = new CollectionRegionVia();
                    $via->setAttributes([
                        'collection_id' => $this->id,
                        'region_id' => $via_id,
                    ]);
                    $via->save();
                }
            }
        }

        if ($this->is_form) {
            CollectionDistrictVia::deleteAll([ 'collection_id' => $this->id ]);

            if(!empty($this->district_ids)){
                foreach ($this->district_ids as $via_id) {
                    $via = new CollectionDistrictVia();
                    $via->setAttributes([
                        'collection_id' => $this->id,
                        'district_id' => $via_id,
                    ]);
                    $via->save();
                }
            }
        }


        $collection = $this;

        $query = Venues::find();
        $query->andWhere(['not in', 'status_id', [0, 3]]);
        $query->andWhere(['is_processed' => 1]);
        $query->andWhere(['agglomeration_id' => $collection->agglomeration_id]);
        $query->andWhere(['and', ['>', 'min_capacity', 1], ['>', 'max_capacity', 1]]);

        if (!empty ($collection->price_person_id)){
            if(!empty($collection->price_person->min) and empty($collection->price_person->max))
                $query->andWhere(['<', 'price_person', $collection->price_person->min]);

            if(!empty($collection->price_person->min) and !empty($collection->price_person->max))
                $query->andWhere(['and', ['>=', 'price_person', $collection->price_person->min], ['<=', 'price_person', $collection->price_person->max]]);

            if(empty($collection->price_person->min) and !empty($collection->price_person->max))
                $query->andWhere(['>', 'price_person', $collection->price_person->max]);
        }
       

        if (!empty ($collection->guest_id)){
            if(!empty($collection->guest->min) and empty($collection->guest->max))
                $query->andWhere(['<=', 'min_capacity', $collection->guest->min]);

            if(!empty($collection->guest->min) and !empty($collection->guest->max))
                $query->andWhere([
                    'and',
                    ['<=', 'min_capacity', $collection->guest->min],
                    ['>=', 'max_capacity', $collection->guest->min],
                    ['<=', 'min_capacity', $collection->guest->max],
                    ['>=', 'max_capacity', $collection->guest->max],
                ]);

            if(empty($collection->guest->min) and !empty($collection->guest->max))
                $query->andWhere(['>', 'max_capacity', $collection->guest->max]);
        }

        // TODO эти свойства переехали из заведения в функ.зона зала
        // if (!empty ($collection->pool))
        //     $query->andWhere(['pool' => 1]);
        // if (!empty ($collection->place_barbecue))
        //     $query->andWhere(['place_barbecue' => 1]);
        // if (!empty ($collection->open_area))
        //     $query->andWhere(['open_area' => 1]);

        $region_ids = ArrayHelper::getColumn($collection->collectionRegionVias, 'region_id');
        $district_ids = ArrayHelper::getColumn($collection->collectionDistrictVias, 'district_id');
      
        if (!empty ($region_ids) and empty ($district_ids)) {
            $query->andWhere(['in', 'region_id', $region_ids]);
        }

        if (!empty ($district_ids)) {
            $query->andWhere(['in', 'district_id', $district_ids]);
        }

        if (!empty ($collection->spec_id)) {
            $query->leftJoin('venues_spec_via', 'venues.id = venues_spec_via.venue_id')
                  ->andWhere(['or', 
                      ['=', 'venues_spec_via.venues_spec_id', $collection->spec_id],
                      ['=', 'venues_spec_via.venues_spec_id', 13],
                  ]);
        }

        // echo $query->createCommand()->getRawSql();
        // die;

        // echo '<pre>';
        // print_r($collection);
        // die;

        $venues = $query->all();

        // echo '<pre>';
        // print_r($venues);
        // die;

        if (!empty ($venues)) {
            $sort = 1;
            $collection_venue = ArrayHelper::getColumn($collection->collectionVenueVias, 'venue_id');
            foreach ($venues as $venue) {
                if (!in_array($venue['id'], $collection_venue)) {
                    $collection_via = new CollectionVenueVia();
                    $collection_via->collection_id = $this->id;
                    $collection_via->venue_id = $venue->id;
                    $collection_via->sort = $sort;
                    $collection_via->active = 1;
                    $collection_via->save(false);
                    $sort++;
                }
            }
        }

    }

}
