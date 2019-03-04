<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Country', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'population',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'header' => '操作',
            'buttons' => [
            'view' => function ($url, $model, $key) { 
            return Html::a('查看', $url, ['title' => '查看'] ); 
            },
            'update' => function ($url, $model, $key) { 
            return Html::a('修改', $url, ['title' => '修改'] ); 
            },
            'delete' => function ($url, $model, $key) { 
            return Html::a('删除', $url, ['title' => '删除'] ); 
            },],

            ], 
        ],
    ]); ?>
</div>
