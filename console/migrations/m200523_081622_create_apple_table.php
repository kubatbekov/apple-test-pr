<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m200523_081622_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }

    public function up()
    {
        $this->createTable('{{%apple}}', [
            'apple_id' => $this->bigPrimaryKey(),
            'status_id' => $this->tinyInteger()->notNull()->unsigned(),
            'color' => $this->string()->notNull(),
            'size' => $this->double()->notNull()->unsigned(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->createIndex('ck_created_at', '{{%apple}}', ['created_at']);
    }
}
