<?php

namespace app\services;

use app\models\Cars;
use app\models\Countries;
use app\models\Models;
use yii\helpers\Json;

/**
 * Сервис для работы с моделью "Models"
 */
class ModelService
{
    /**
     * Создание записей в таблице "Models" по указанной стране и указанному языку
     */
    public function insertModels() : void
    {
        $countryCode = $_POST['CarModelsForm']['country'];
        $langCode = $_POST['CarModelsForm']['lang'];

        $models = Json::decode(file_get_contents("https://cardbp.toyota-europe.com/v2/brand/toyota/country/{$countryCode}/language/{$langCode}/models"));

        foreach ($models['Models'] as $model) {

            $modifiedDate = explode('-', $model['ModifiedOn']);
            $modifiedTimestamp = mktime(0, 0, 0, $modifiedDate[1], $modifiedDate[2], $modifiedDate[0]);

            $country = Countries::findOne(['code' => $model['Context']['Country']]);

            /** @var Models $isExistModel */
            $isExistModel = Models::find()
                ->where([
                    'name'       => $model['Name'],
                    'country_id' => $country->id
                ])
                ->one();

            if($isExistModel == null) {
                $newModel = new Models();
                $newModel->setAttributes([
                    'name'       => $model['Name'],
                    'country_id' => $country->id
                ]);

                if($newModel->save()) {
                    $this->insertCars($newModel, $countryCode, $langCode, $modifiedTimestamp);
                }
            } else {
                $this->insertCars($isExistModel, $countryCode, $langCode, $modifiedTimestamp);
            }

        }
    }

    /**
     * Создание записей в таблице "Cars" по указанной модели
     *
     * @param Models $model
     * @param string $countryCode
     * @param string $langCode
     * @param int $modifiedTimestamp
     */
    public function insertCars(Models $model, string $countryCode, string $langCode, int $modifiedTimestamp) : void
    {
        $options = [
            'https'=> [
                'method'=>"GET",
                'header'=> ["Content-Type: application/x-www-form-urlencoded \r\n"]
            ]
        ];
        $url = "https://cardbp.toyota-europe.com/v2/brand/toyota/country/{$countryCode}/language/{$langCode}/model/{$model->name}/cars";
        $url = str_replace(" ", "%20", $url);

        $context = stream_context_create($options);

        $cars = file_get_contents($url, false, $context);
        $cars = Json::decode($cars);

        foreach ($cars['Cars'] as $car) {

            /** @var Cars $isExistCar */
            $isExistCar = Cars::find()
                ->where(['name' => $car['Name'],])
                ->one();

            if (!$isExistCar) {
                $newCar = new Cars();
                $newCar->setAttributes([
                    'model_id'            => $model->id,
                    'name'                => $car['Name'],
                    'description'         => $car['Body']['Name'],
                    'engine'              => $car['Engine']['Name'],
                    'grade'               => $car['Grade']['Name'],
                    'transmission'        => $car['Transmission']['Name'],
                    'wheel_drive'         => $car['WheelDrive']['Name'],
                    'list_price'          => $car['Price']['ListPrice'],
                    'list_price_discount' => $car['Price']['ListPriceWithDiscount']
                ]);
                $newCar->save();
            } else {
               if($isExistCar->updated_at < $modifiedTimestamp) {
                   $isExistCar->setAttributes([
                       'model_id'            => $model->id,
                       'name'                => $car['Name'],
                       'description'         => $car['Body']['Name'],
                       'engine'              => $car['Engine']['Name'],
                       'grade'               => $car['Grade']['Name'],
                       'transmission'        => $car['Transmission']['Name'],
                       'wheel_drive'         => $car['WheelDrive']['Name'],
                       'list_price'          => $car['Price']['ListPrice'],
                       'list_price_discount' => $car['Price']['ListPriceWithDiscount']
                   ]);
                   $isExistCar->update();
               }
            }
        }

    }
}