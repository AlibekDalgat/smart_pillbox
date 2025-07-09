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
            $times = is_array($reminder->time) ? $reminder->time : json_decode($reminder->time, true);
            if (!is_array($times)) {
                Yii::error("Invalid time format for reminder ID: {$reminder->id}", 'reminder');
                continue;
            }
            foreach ($times as $time) {
                $reminderTime = \DateTime::createFromFormat('H:i', $time);
                if (!$reminderTime) {
                    Yii::error("Invalid time format '{$time}' for reminder ID: {$reminder->id}", 'reminder');
                    continue;
                }
                $reminderTime->setDate((int)$now->format('Y'), (int)$now->format('m'), (int)$now->format('d'));
                $diff = abs($now->getTimestamp() - $reminderTime->getTimestamp()) / 60;
                if ($diff <= 10) {
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