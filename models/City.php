<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $name
 * @property string $ref
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Street[] $streets
 */
class City extends \yii\db\ActiveRecord
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
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ref'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['ref'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('city', 'ID'),
            'name' => Yii::t('city', 'Name'),
            'ref' => Yii::t('city', 'Ref'),
            'created_at' => Yii::t('city', 'Created At'),
            'updated_at' => Yii::t('city', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStreets()
    {
        return $this->hasMany(Street::className(), ['city_id' => 'id']);
    }

    public static function findModel($ref)
    {
        if(($model = self::findOne(['ref' => $ref])) !== null)
            return $model;
        else
            return false;
    }

    public static function getCityName()
    {
        $city = self::find()->all();

        return ArrayHelper::map($city, 'id', 'name');
    }
}
