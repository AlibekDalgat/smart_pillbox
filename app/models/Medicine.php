<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Medicine extends ActiveRecord
{
    public static function tableName()
    {
        return 'medicine';
    }

    public function rules()
    {
        return [
            [['name', 'dose', 'user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['name', 'dose'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}