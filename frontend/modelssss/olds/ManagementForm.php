<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\models\DB;

/**
 * Signup form
 */
class ManagementForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirmPassword;
    public $telNumber;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            
            ['confirmPassword', 'required'],
            ['confirmPassword', 'string', 'min' => 6],
            
            ['telNumber', 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        
        return $user->save() ? $user : null;
    }
    
    public function insert_user()
    {
        $user = new ZOAM_DB();
        $user->name = $this->$name;
        $user->email = $this->$email;
        $user->password = $this->$password;
        $user->insert();
    }
    
    public function select_user()
    {
        
    }
}