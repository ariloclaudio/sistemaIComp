<?php
namespace backend\models;
use yiibr\brvalidator\CpfValidator;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $nome;

    public $visualizacao_candidatos;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['nome', 'required'],
            ['nome', 'string'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Já existe usuário cadastrado com esse CPF'],
            [['username'], CpfValidator::className(), 'message' => 'CPF Inválido'],
            ['username', 'string'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['nome', 'string', 'max' => 255],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Esta senha não é igual à anterior" ],

        ];
    }

public function attributeLabels()
    {

        return [
            'username' => 'CPF (Digite somente números)',
            'password' => 'Senha',
            'password_repeat' => 'Senha novamente',
            'email' => 'E-mail',
         ];
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->nome = $this->nome;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->visualizacao_candidatos =  date("Y-m-d H:i:s");
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }


    protected function findModel($id)
    {
        if (($model = SignupForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




}