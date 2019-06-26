<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StreetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('street', 'Streets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="street-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'city_id',
                'label' => 'Родительская категория',
                'format' => 'text',
                'content' => function($data){
                    return $data->getCityName();
                },
                'filter' => \app\models\City::getCityName()
            ],
            'name',
            'ref',
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'dd.MM.YYYY HH:mm']
            ],
            //'updated_at',
        ],
    ]); ?>


</div>
