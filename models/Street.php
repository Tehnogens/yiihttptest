<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "street".
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string $ref
 * @property int $created_at
 * @property int $updated_at
 *
 * @property City $city
 */
class Street extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'street';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'name', 'ref'], 'required'],
            [['city_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['ref'], 'string', 'max' => 40],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('street', 'ID'),
            'city_id' => Yii::t('street', 'City ID'),
            'name' => Yii::t('street', 'Name'),
            'ref' => Yii::t('street', 'Ref'),
            'created_at' => Yii::t('street', 'Created At'),
            'updated_at' => Yii::t('street', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public static function findModel($ref)
    {
        if(($model = self::findOne(['ref' => $ref])) !== null)
            return $model;
        else
            return false;
    }

    public function getCityName()
    {
        $city = $this->city;

        return $city ? $city->name : '';
    }
}
