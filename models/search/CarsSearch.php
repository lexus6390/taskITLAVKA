<?php

namespace app\models\search;

use yii\data\ActiveDataProvider;
use app\models\Cars;

/**
 * Поисковая модель для таблицы `Cars`.
 */
class CarsSearch extends Cars
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'list_price', 'list_price_discount', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'engine', 'grade', 'transmission', 'wheel_drive'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $id)
    {
        $query = Cars::find()->where(['model_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'model_id' => $this->model_id,
            'list_price' => $this->list_price,
            'list_price_discount' => $this->list_price_discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'engine', $this->engine])
            ->andFilterWhere(['like', 'grade', $this->grade])
            ->andFilterWhere(['like', 'transmission', $this->transmission])
            ->andFilterWhere(['like', 'wheel_drive', $this->wheel_drive]);

        return $dataProvider;
    }
}
