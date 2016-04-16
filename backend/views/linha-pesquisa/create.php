<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LinhaPesquisa */

$this->title = 'Criar Linha de Pesquisa';
$this->params['breadcrumbs'][] = ['label' => 'Linha Pesquisas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linha-pesquisa-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
