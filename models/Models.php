<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Класс модели автомобилей "models".
 *
 * @property int $id
 * @property string $name     Название модели авто
 * @property int $country_id  Идентификатор страны
 * @property int $created_at  Создано
 * @property int $updated_at  Изменено
 *
 * @property Cars[] $cars
 * @property Countries $country
 */
class Models extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'models';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [
                ['country_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Countries::class,
                'targetAttribute' => ['country_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'name'       => 'Марка',
            'country_id' => 'Страна',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCars()
    {
        return $this->hasMany(Cars::class, ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['id' => 'country_id']);
    }

    /**
     * Получение количества автомобилей определенной модели
     * @return int|string
     */
    public function getCountCars()
    {
        return Cars::find()->where(['model_id' => $this->id])->count();
    }
}
