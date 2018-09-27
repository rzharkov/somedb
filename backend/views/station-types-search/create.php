<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StationType */

$this->title = 'Create Station Type';
$this->params['breadcrumbs'][] = ['label' => 'Station Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="station-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
