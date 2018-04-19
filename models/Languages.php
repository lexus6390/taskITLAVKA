<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс модели "languages".
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 *
 * @property Countries[] $countries
 * @property LanguagesMultiple[] $langMultiples
 * @property Countries $country
 * @property Models[] $models
 */
class Languages extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'languages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id'], 'integer'],
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
            'name'       => 'Name',
            'country_id' => 'Country ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Countries::class, ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLangMultiples()
    {
        return $this->hasMany(LanguagesMultiple::class, ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(Models::class, ['lang_id' => 'id']);
    }
}
