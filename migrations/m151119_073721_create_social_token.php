<?php

use yii\db\Migration;
use yii\db\Schema;

class m151119_073721_create_social_token extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%social_token}}', [
            'id' => Schema::TYPE_PK . ' NOT NULL',
            'service' => Schema::TYPE_STRING . '(32) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sid' => Schema::TYPE_STRING . '(255) NOT NULL',
            'access_token' => Schema::TYPE_STRING . '(250) NOT NULL',
            'expires' => Schema::TYPE_STRING . '(250)',
            'refresh_token' => Schema::TYPE_STRING . '(250)',
            'params' => Schema::TYPE_TEXT
        ]);
        $this->addForeignKey('fk_token_user', '{{%social_token%}}', 'user_id', '{{user}}', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_token_user', '{{%social_token%}}');
        $this->dropTable('{{%social_token%}}');
    }
}
