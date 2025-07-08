<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ReminderLog extends ActiveRecord
{
    public static function tableName()
    {
        return 'reminder_log';
    }

    public function rules()
    {
        return [
            [['reminder_id', 'created_at'], 'required'],
            [['reminder_id', 'taken_at', 'created_at'], 'integer'],
            [['reminder_id'], 'exist', 'targetClass' => Reminder::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getReminder()
    {
        return $this->hasOne(Reminder::class, ['id' => 'reminder_id']);
    }
}