<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Reminder extends ActiveRecord
{
    public static function tableName()
    {
        return 'reminder';
    }

    public function rules()
    {
        return [
            [['medicine_id', 'time', 'begin_date', 'finish_date'], 'required'],
            [['medicine_id', 'created_at'], 'integer'],
            [['time'], 'safe'], // JSON
            [['begin_date', 'finish_date'], 'date', 'format' => 'php:Y-m-d'],
            [['comment'], 'string'],
            [['medicine_id'], 'exist', 'targetClass' => Medicine::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getMedicine()
    {
        return $this->hasOne(Medicine::class, ['id' => 'medicine_id']);
    }
}