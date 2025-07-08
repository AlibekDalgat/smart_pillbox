<?php
namespace app\commands;

use yii\console\Controller;
use app\models\User;

class UserController extends Controller
{
    public function actionCreate($email, $name, $password)
    {
        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->setPassword($password);
        $user->created_at = time();
        if ($user->save()) {
            $this->stdout("User created: {$email}\n");
        } else {
            $this->stderr("Failed to create user: " . print_r($user->errors, true) . "\n");
        }
    }
}