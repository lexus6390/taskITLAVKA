<?php

use app\models\Countries;
use app\models\Languages;
use app\models\LanguagesMultiple;
use yii\db\Migration;
use yii\helpers\Json;

/**
 * Миграция заполнения таблиц `languages` и `countries`
 */
class m180419_065922_add_languages_and_countries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $countries = Json::decode(file_get_contents('https://cardbp.toyota-europe.com/v2/brand/toyota/countries'));

        foreach ($countries['Countries'] as $country) {

            $langsOfCountry = [];
            foreach ($country['Languages'] as $language) {

                // проверяем есть уже такой язык в БД
                $isExistLang = Languages::findOne(['code' => $language['Code']]);

                // если есть, записываем его id
                if($isExistLang) {
                    $langsOfCountry[] = $isExistLang->id;
                } else {
                    // иначе создаем новую запись
                    $newLang = new Languages();
                    $newLang->setAttributes([
                        'name' => $language['Name'],
                        'code' => $language['Code']
                    ]);
                    if($newLang->save()) {
                        $langsOfCountry[] = $newLang->id;
                    }
                }
            }

            $newCountry = new Countries();
            $newCountry->setAttributes([
                'name' => $country['Name'],
                'code' => $country['Code'],
            ]);

            /** @var Languages[] $langsOfCountry */
            if(count($langsOfCountry) == 1) {
                $newCountry->setAttribute('lang_id', $langsOfCountry[0]);
                $newCountry->save();
            } else {
                $newCountry->setAttribute('is_multiple_lang', '1');
                $newCountry->save();

                foreach ($langsOfCountry as $language) {
                    $newLangsMultiple = new LanguagesMultiple();
                    $newLangsMultiple->setAttributes([
                        'country_id' => $newCountry->id,
                        'lang_id'    => $language
                    ]);
                    $newLangsMultiple->save();
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180419_065922_add_languages_and_countries cannot be reverted.\n";

        return false;
    }
}
