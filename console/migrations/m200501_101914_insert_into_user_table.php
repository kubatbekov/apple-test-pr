<?php

use yii\db\Migration;

class m200501_101914_insert_into_user_table extends Migration
{
    private $tableName = '{{%user}}';

    private $userId = 1;

    private $status = 10;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function safeUp()
    {
        $now = gmdate('U');
        $columns = [
            'id' => $this->userId,
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'email' => 'admin@example.com',
            'status' => $this->status,
            'password_hash' => Yii::$app->security->generatePasswordHash('admin123'),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $this->getDb()
            ->createCommand()
            ->insert($this->tableName, $columns)
            ->execute();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function safeDown()
    {
        $this->getDb()
            ->createCommand()
            ->delete($this->tableName, ['id' => $this->tableName])
            ->execute();
    }
}
