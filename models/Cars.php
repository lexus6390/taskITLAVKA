<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Класс модели "cars".
 *
 * @property int $id
 * @property int $model_id             Идентификатор модели авто
 * @property string $name              Название авто
 * @property string $description       Описание авто
 * @property string $engine            Двигатель
 * @property string $grade             Класс авто
 * @property string $transmission      Трансмиссия
 * @property string $wheel_drive       Привод
 * @property int $list_price           Цена
 * @property int $list_price_discount  Цена со скидкой
 * @property int $created_at           Создано
 * @property int $updated_at           Изменено
 *
 * @property Models $model
 */
class Cars extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cars';
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
            [
                [
                    'model_id',
                    'list_price',
                    'list_price_discount',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'description',
                    'engine',
                    'grade',
                    'transmission',
                    'wheel_drive'
                ],
                'string', 'max' => 255
            ],
            [
                ['model_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Models::class,
                'targetAttribute' => ['model_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => 'ID',
            'model_id'            => 'Model ID',
            'name'                => 'Название',
            'description'         => 'Description',
            'engine'              => 'Двигатель',
            'grade'               => 'Класс авто',
            'transmission'        => 'Трансмиссия',
            'wheel_drive'         => 'Привод',
            'list_price'          => 'Цена',
            'list_price_discount' => 'Цена со скидкой',
            'created_at'          => 'Создано',
            'updated_at'          => 'Изменено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(Models::class, ['id' => 'model_id']);
    }
}
