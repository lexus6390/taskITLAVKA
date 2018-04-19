<?php

namespace app\models\forms;

use yii\base\Model;

/**
 * Форма для загрузки и обновления данных по моделям автомобилей.
 */
class CarModelsForm extends Model
{
    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $lang;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'lang'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country' => 'Страна',
            'lang'    => 'Язык'
        ];
    }
}