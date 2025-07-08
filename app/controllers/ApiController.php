<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;
use app\models\Medicine;
use app\models\Reminder;
use app\models\ReminderLog;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class ApiController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['login'],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $params = Yii::$app->request->post();
        $user = User::findOne(['email' => $params['email'] ?? '']);
        if (!$user || !$user->validatePassword($params['password'] ?? '')) {
            Yii::$app->response->setStatusCode(401);
            return ['error' => 'Invalid credentials'];
        }

        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('JfzjwgqQn85xuX7PkpE8KlF7hsuJ1OYyIAiq76pzYpI='));
        $token = $config->builder()
            ->issuedBy('http://localhost:8080')
            ->permittedFor('http://localhost:8080')
            ->identifiedBy(uniqid())
            ->issuedAt(new \DateTimeImmutable())
            ->expiresAt((new \DateTimeImmutable())->modify('+1 hour'))
            ->withClaim('uid', $user->id)
            ->getToken($config->signer(), $config->signingKey());

        return [
            'token' => $token->toString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ];
    }

    public function actionMedicineIndex()
    {
        return Medicine::find()->where(['user_id' => Yii::$app->user->id])->all();
    }

    public function actionMedicineCreate()
    {
        $model = new Medicine();
        $model->user_id = Yii::$app->user->id;
        $model->created_at = time();
        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return $model;
        }
        Yii::$app->response->setStatusCode(422);
        return ['errors' => $model->errors];
    }

    public function actionReminderIndex()
    {
        $today = date('Y-m-d');
        return Reminder::find()
            ->where(['<=', 'begin_date', $today])
            ->andWhere(['>=', 'finish_date', $today])
            ->all();
    }

    public function actionReminderCreate()
    {
        $model = new Reminder();
        $model->created_at = time();
        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return $model;
        }
        Yii::$app->response->setStatusCode(422);
        return ['errors' => $model->errors];
    }

    public function actionReminderTake($id)
    {
        $reminder = Reminder::findOne($id);
        if (!$reminder || $reminder->medicine->user_id !== Yii::$app->user->id) {
            Yii::$app->response->setStatusCode(404);
            return ['error' => 'Reminder not found'];
        }
        $log = new ReminderLog();
        $log->reminder_id = $id;
        $log->taken_at = time();
        $log->created_at = time();
        if ($log->save()) {
            return ['status' => 'success'];
        }
        Yii::$app->response->setStatusCode(422);
        return ['errors' => $log->errors];
    }

    public function actionReminderDelete($id)
    {
        $reminder = Reminder::findOne($id);
        if (!$reminder || $reminder->medicine->user_id !== Yii::$app->user->id) {
            Yii::$app->response->setStatusCode(404);
            return ['error' => 'Reminder not found'];
        }
        $reminder->delete();
        return ['status' => 'success'];
    }
}