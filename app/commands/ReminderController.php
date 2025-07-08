<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Reminder;
use app\models\ReminderLog;

class ReminderController extends Controller
{
    public function actionCheck()
    {
        $now = new \DateTime();
        $currentTime = $now->format('H:i');
        $today = $now->format('Y-m-d');

        $reminders = Reminder::find()
            ->where(['<=', 'begin_date', $today])
            ->andWhere(['>=', 'finish_date', $today])
            ->all();

        foreach ($reminders as $reminder) {
            $times = json_decode($reminder->time, true);
            foreach ($times as $time) {
                // Проверяем, наступило ли время напоминания (в пределах 10 минут)
                $reminderTime = \DateTime::createFromFormat('H:i', $time);
                $diff = abs($now->getTimestamp() - $reminderTime->setDate($now->getYear(), $now->getMonth(), $now->getDay())->getTimestamp()) / 60;
                if ($diff <= 10) {
                    // Проверяем, не был ли приём уже отмечен
                    $taken = ReminderLog::find()
                        ->where(['reminder_id' => $reminder->id])
                        ->andWhere(['>=', 'taken_at', strtotime($today . ' 00:00:00')])
                        ->andWhere(['<=', 'taken_at', strtotime($today . ' 23:59:59')])
                        ->exists();
                    if (!$taken) {
                        Yii::info("Напоминание: Пора принять {$reminder->medicine->name} в {$time} (ID: {$reminder->id})", 'reminder');
                    }
                }
            }
        }
    }
}