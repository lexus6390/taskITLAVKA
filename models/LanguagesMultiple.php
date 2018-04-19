<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс модели "lang_multiple".
 *
 * @property int $country_id  Идентификатор страны
 * @property int $lang_id     Идентификатор языка
 *
 * @property Countries $country
 * @property Languages $languages
 */
class LanguagesMultiple extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang_multiple';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'lang_id'], 'required'],
            [['country_id', 'lang_id'], 'integer'],
            [
                ['country_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Countries::class,
                'targetAttribute' => ['country_id' => 'id']
            ],
            [
                ['lang_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Languages::class,
                'targetAttribute' => ['lang_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'lang_id'    => 'Lang ID',
        ];
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
    public function getLanguages()
    {
        return $this->hasOne(Languages::class, ['id' => 'lang_id']);
    }
}
