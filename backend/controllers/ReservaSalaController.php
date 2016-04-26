<?php

namespace backend\controllers;

use Yii;
use yii\base\NotSupportedException;
use app\models\ReservaSala;
use app\models\ReservaSalaSearch;
use app\models\SalaSearch;
use app\models\Sala;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReservaSalaController implements the CRUD actions for ReservaSala model.
 */
class ReservaSalaController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                           return Yii::$app->user->identity->checarAcesso('coordenador') || Yii::$app->user->identity->checarAcesso('secretaria') ||
                           Yii::$app->user->identity->checarAcesso('professor');
                        }
                    ],                    
                ],
            ], 
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new SalaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCalendario(){
        $reservasCalendario = array();
        $idSala = filter_input(INPUT_GET, 'idSala');

        $modelSalas = Sala::find()->all();
        if($idSala)
            $modelSala = Sala::findOne(['id' => $idSala]);
        else
            $modelSala = $modelSalas[0];
        
        $reservas = ReservaSala::findAll(['sala' => $idSala]);
        foreach ($reservas as $reserva) {
            $reservaItem = new \yii2fullcalendar\models\Event();
            $reservaItem->id = $reserva->id;
            $reservaItem->title = $reserva->atividade;
            $reservaItem->start = $reserva->dataInicio.'T'.$reserva->horaInicio;
            $reservaItem->end = $reserva->dataTermino.'T'.$reserva->horaTermino;
            $reservasCalendario[] = $reservaItem;
        }

        return $this->render('calendario',[
            'modelSala' => $modelSala,
            'reservasCalendario' => $reservasCalendario,
            'modelSalas' => $modelSalas,
        ]);
    }

    public function actionListagemreservas(){
        
        $searchModel = new ReservaSalaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('listagemReservas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($sala, $dataInicio, $horaInicio)
    {
        $model = new ReservaSala();
        $model->sala = $sala;
        $model->dataInicio = $dataInicio;
        $model->dataTermino = $model->dataInicio;
        $model->horaInicio = $horaInicio;


        if($model->salaDesc->reservasAtivas > 4){
            $this->mensagens('warning', 'Limite de Reservas', 'Você alcançou o limite de 5 reservas ativas.');
            return $this->redirect(['calendario', 'idSala' => $model->sala]);
        }else if($model->dataInicio < date('Y-m-d')){
            $this->mensagens('warning', 'Data Inválida', 'A data para reserva deve ser igual ou superior que a data de hoje.');
            return $this->redirect(['calendario', 'idSala' => $model->sala]);
        }else if(!$model->horarioOk()){
            $this->mensagens('danger', 'Horário Inválido', 'Não foi possível reservar esta sala no horário escolhido, pois ela já possui uma reserva. Tente novamente em outro horário!');
            return $this->redirect(['calendario', 'idSala' => $model->sala]);
        }
        
        $model->idSolicitante = Yii::$app->user->identity->id;
        $model->dataReserva = date('Y-m-d H:i:s');      
        
        if ($model->load(Yii::$app->request->post())) {
            if(!$model->horarioOk()){
                $this->mensagens('danger', 'Horário Inválido', 'Não foi possível reservar esta sala no horário escolhido, pois ela já possui uma reserva. Tente novamente em outro horário!');
                return $this->redirect(['calendario', 'idSala' => $model->sala]);
            }
            if($model->save()){
                $this->mensagens('success', 'Reserva de Sala', 'A \''.$model->atividade.'\' foi reservada com sucesso.');
                return $this->redirect(['calendario', 'idSala' => $model->sala]);
            }else{
                $this->mensagens('danger', 'Erro ao Reservar Sala', 'Ocorreu um erro ao reservar a sala. Verifique os campos e tente novamente.');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            if(filter_input(INPUT_GET, 'requ') != 'AJAX')
                return $this->redirect(['index']);
            else
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
        }
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->validaUpdateDelete($model);

        $model->dataInicio = date('d-m-Y', strtotime($model->dataInicio));
        $model->dataTermino = date('d-m-Y', strtotime($model->dataTermino));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->save()){
                $this->mensagens('success', 'Reserva de Sala', 'A reserva \''.$model->atividade.'\' foi Alterada com sucesso.');
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                $this->mensagens('danger', 'Erro ao Reservar Sala', 'Ocorreu um erro ao alterar a reserva de sala. Verifique os campos e tente novamente.');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->validaUpdateDelete($model);

        if($model->delete()){
            $this->mensagens('success', 'Reservar Sala', 'A reserva de sala foi removida com sucessso.');
        }else{
            $this->mensagens('danger', 'Erro ao remover Reserva Sala', 'Ocorreu um erro ao remover a reserva de sala.');
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = ReservaSala::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('A página solicitada não existe.');
        }
    }

    protected function validaUpdateDelete($model){
        if($model->idSolicitante != Yii::$app->user->identity->id && $model->dataInicio < date('d-m-Y') 
            || ($model->dataInicio == date('d-m-Y') && $model->horaInicio < date('H:i:s')))
            throw new ForbiddenHttpException('Acesso negado.');
    }

    /* Envio de mensagens para views
    Tipo: success, danger, warning*/
    protected function mensagens($tipo, $titulo, $mensagem){
        Yii::$app->session->setFlash($tipo, [
            'type' => $tipo,
            'icon' => 'home',
            'duration' => 5000,
            'message' => $mensagem,
            'title' => $titulo,
            'positonY' => 'top',
            'positonX' => 'center',
            'showProgressbar' => true,
        ]);
    }
}
