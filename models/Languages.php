<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс модели "languages".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @property LanguagesMultiple[] $langMultiples
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
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => 'Name',
            'code' => 'Code',
        ];
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
    public function getModels()
    {
        return $this->hasMany(Models::class, ['lang_id' => 'id']);
    }
}
