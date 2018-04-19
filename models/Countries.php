<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс модели "countries".
 *
 * @property int $id
 * @property string $name           Название страны
 * @property string $code           Кодовое обозначение
 * @property int $lang_id           Идентификатор языка
 * @property int $is_multiple_lang  Есть ли в стране несколько языков
 *
 * @property Languages $lang
 * @property LanguagesMultiple[] $langMultiples
 * @property Languages[] $languages
 * @property Models[] $models
 */
class Countries extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
            [['is_multiple_lang'], 'string', 'max' => 1],
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
            'id'               => 'ID',
            'name'             => 'Name',
            'code'             => 'Code',
            'lang_id'          => 'Lang ID',
            'is_multiple_lang' => 'Is Multiple Lang',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Languages::class, ['id' => 'lang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLangMultiples()
    {
        return $this->hasMany(LanguagesMultiple::class, ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Languages::class, ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(Models::class, ['country_id' => 'id']);
    }
}
