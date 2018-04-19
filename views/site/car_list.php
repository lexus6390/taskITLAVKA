<?php

use app\models\Models;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ModelsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Модели';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="models-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'country_id',
                'value'     => 'country.name'
            ],
            [
                'label' => 'Количество автомобилей',
                'value' => function ($model) {
                    /** @var Models $model */
                    return $model->getCountCars();
                }
            ],
            [
                'attribute' => 'updated_at',
                'value'     => function ($model) {
                    return \Yii::$app->formatter->asDate($model->updated_at, 'Y LLL d');
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>