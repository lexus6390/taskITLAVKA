<?php

use yii\db\Migration;

/**
 * Миграция создания таблиц `models`, `cars`, `languages`, `countries`.
 */
class m180419_073759_create_base_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // создание таблицы моделей автомобилей 'models'
        $this->createTable('models', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull(),
            'country_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // создание таблицы автомобилей 'cars'
        $this->createTable('cars', [
            'id'                  => $this->primaryKey(),
            'model_id'            => $this->integer(),
            'name'                => $this->string(),
            'description'         => $this->string(),
            'engine'              => $this->string(),
            'grade'               => $this->string(),
            'transmission'        => $this->string(),
            'wheel_drive'         => $this->string(),
            'list_price'          => $this->integer(),
            'list_price_discount' => $this->integer(),
            'created_at'          => $this->integer(),
            'updated_at'          => $this->integer()
        ]);

        // создание таблицы языков 'languages'
        $this->createTable('languages', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(),
            'country_id' => $this->integer()
        ]);

        // создание таблицы стран 'countries'
        $this->createTable('countries', [
            'id'               => $this->primaryKey(),
            'name'             => $this->string(),
            'code'             => $this->string(),
            'lang_id'          => $this->integer(),
            'is_multiple_lang' => $this->boolean()->defaultValue(false)
        ]);

        // создание промежуточной таблицы для хранения множественных значений языков страны 'lang_multiple'
        $this->createTable('lang_multiple', [
            'country_id'  => $this->integer()->notNull(),
            'lang_id'     => $this->integer()->notNull()
        ]);

        // внешний ключ models.country_id = countries.id
        $this->addForeignKey(
            'fk-models-country_id-countries-id',
            'models',
            'country_id',
            'countries',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        // внешний ключ cars.model_id = models.id
        $this->addForeignKey(
            'fk-cars-model_id-models-id',
            'cars',
            'model_id',
            'models',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        // внешний ключ languages.country_id = countries.id
        $this->addForeignKey(
            'fk-languages-country_id-countries-id',
            'languages',
            'country_id',
            'countries',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        // внешний ключ countries.lang_id = languages.id
        $this->addForeignKey(
            'fk-countries-lang_id-languages-id',
            'countries',
            'lang_id',
            'languages',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        // внешний ключ lang_multiple.country_id = countries.id
        $this->addForeignKey(
            'fk-lang_multiple-country_id-countries-id',
            'lang_multiple',
            'country_id',
            'countries',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // внешний ключ lang_multiple.lang_id = languages.id
        $this->addForeignKey(
            'fk-lang_multiple-lang_id-languages-id',
            'lang_multiple',
            'lang_id',
            'languages',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // удаление внешних ключей таблицы 'lang_multiple'
        $this->dropForeignKey('fk-lang_multiple-lang_id-languages-id', 'lang_multiple');
        $this->dropForeignKey('fk-lang_multiple-country_id-countries-id', 'lang_multiple');

        // удаление внешних ключей таблицы 'countries'
        $this->dropForeignKey('fk-countries-lang_id-languages-id', 'countries');

        // удаление внешних ключей таблицы 'languages'
        $this->dropForeignKey('fk-languages-country_id-countries-id', 'languages');

        // удаление внешних ключей таблицы 'cars'
        $this->dropForeignKey('fk-cars-model_id-models-id', 'cars');

        // удаление внешних ключей таблицы 'models'
        $this->dropForeignKey('fk-models-country_id-countries-id', 'models');

        // удаление таблиц
        $this->dropTable('models');
        $this->dropTable('cars');
        $this->dropTable('languages');
        $this->dropTable('countries');
        $this->dropTable('lang_multiple');
    }
}
