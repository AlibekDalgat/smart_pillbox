<?php

use yii\db\Migration;

class m250707_191500_create_tables extends Migration
{
    public function safeUp()
    {
        // Таблица пользователей
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Таблица лекарств
        $this->createTable('medicine', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'dose' => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-medicine-user_id', 'medicine', 'user_id', 'user', 'id', 'CASCADE');

        // Таблица напоминаний
        $this->createTable('reminder', [
            'id' => $this->primaryKey(),
            'medicine_id' => $this->integer()->notNull(),
            'time' => $this->json()->notNull(), // Храним массив времени в формате JSON
            'begin_date' => $this->date()->notNull(),
            'finish_date' => $this->date()->notNull(),
            'comment' => $this->text(),
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-reminder-medicine_id', 'reminder', 'medicine_id', 'medicine', 'id', 'CASCADE');

        // Таблица логов приёма
        $this->createTable('reminder_log', [
            'id' => $this->primaryKey(),
            'reminder_id' => $this->integer()->notNull(),
            'taken_at' => $this->integer(), // Время фактического приёма (null, если не принят)
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-reminder_log-reminder_id', 'reminder_log', 'reminder_id', 'reminder', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('reminder_log');
        $this->dropTable('reminder');
        $this->dropTable('medicine');
        $this->dropTable('user');
    }
}