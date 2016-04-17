<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Defesa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="defesa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data', ['options' => []])->widget(DatePicker::classname(), [
        'language' => Yii::$app->language,
        'options' => ['placeholder' => 'Selecione a Data da Defesa ...',],
        'pluginOptions' => [
            'format' => 'dd-mm-yyyy',
            'todayHighlight' => true
        ]
    ])->label("<font color='#FF0000'>*</font> <b>Data da Defesa: </b>")
?>

    <?= $form->field($model, 'horario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'resumo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'examinador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emailExaminador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'previa')->FileInput(['accept' => '.pdf'])->label("Prévia (PDF)"); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Salvar Alterações', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
