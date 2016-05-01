<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = "Detalhes da Defesa";
$this->params['breadcrumbs'][] = ['label' => 'Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="defesa-view">

    <p>
    <div class="row" style="margin-left: 10px;">

        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['defesa/index',], ['class' => 'btn btn-warning']) ?>  

		<?= $model->conceito == null ? Html::a('<span class="glyphicon glyphicon-edit"></span> Editar', ['update', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-primary']) : "" ?>
        
        <?= $model->banca->status_banca == null ? Html::a('<span class="glyphicon glyphicon-remove"></span> Excluir', ['delete', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Deseja remove defesa \''.$model->titulo.'\'?',
                'method' => 'post',
            ],
        ]) : "" ?>

        <?php if(Yii::$app->user->identity->secretaria){
                Modal::begin([
                  'header' => '<h2>Lançar Conceito</h2>',
                  'toggleButton' => ['label' => '<span class="fa fa-hand-stop-o"></span> Lançar Conceito', 'class' => 'btn btn-success'],
                  'id' => 'modal',
                  'size' => 'modal-md',
                ]);

                $form = ActiveForm::begin();
                echo $form->field($model, 'conceito')->dropDownlist(['Aprovado' => 'Aprovado', 'Reprovado' => 'Reprovado', 'Suspenso' => 'Suspenso'], 
                    ['prompt' => 'Selecione um Conceito']);
                
                echo "<div class='form-group'>";
                echo Html::submitButton($model->isNewRecord ? 'Criar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                echo "</div>";

                ActiveForm::end();

                Modal::end();
            }
        ?>
        <?= Yii::$app->user->identity->secretaria ? Html::a('<span class="glyphicon glyphicon-envelope"></span>  Enviar Lembrete de Pendência', ['lembretependencia', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Html::a('<span class="glyphicon glyphicon-print"></span>  Convite', ['convitepdf', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success', 'target' => '_blank']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-print"></span> Ata Defesa  ', ['atapdf', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success', 'target' => '_blank']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-print"></span> Folha de Qualificação', ['update', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id], ['class' => 'btn btn-success', 'target' => '_blank']) ?>
        </div>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nome',
            [
                'label' => 'E-mail',
                'value' => $model->modelAluno->email,
            ],
            'curso',
            'titulo',
            [
            'attribute' => 'numDefesa',
            'label' => 'Nº da Defesa',
            ]
            ,
            [
            "attribute" => 'tipodefesa',
            "label" => "Tipo",
            ],

            [
            "attribute" => 'data',
            "value" => date("d/m/Y", strtotime($model->data))
            ],
            [
            "attribute" => 'conceitodefesa',
            'format' => 'html',
            "label" => "Conceito",
            ],
            [
            "attribute" => 'previa',
            'format' => 'raw',
              'value' => "<a href='previa/".$model->previa."' target = '_blank'> Baixar </a>"
            ],

            [
            'attribute' => 'horario',
            'visible' => ($model->curso == "Doutorado" && $model->tipoDefesa == "Q1") ? false : true,
            ]
            ,

            [
            'attribute' => 'local',
            'visible' => ($model->curso == "Doutorado" && $model->tipoDefesa == "Q1") ? false : true,
            ]
            ,
            'resumo:ntext',
            //'banca_id',
        ],
    ]) ?>

<h3> Detalhes da Banca </h3>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        "summary" => "",
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'banca_id',
            //'membrosbanca_id',
            [
                'attribute'=>'membro_nome',
                'label' => "Nome do Membro",
            ],
            [
                'attribute'=>'membro_filiacao',
                'label' => "Filiação do Membro",
            ],
            [
                "attribute" => 'funcaomembro',
                "label" => "Função",
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
