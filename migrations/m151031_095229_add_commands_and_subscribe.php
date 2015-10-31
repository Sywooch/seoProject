<?php

use yii\db\Migration;
use yii\db\Schema;

class m151031_095229_add_commands_and_subscribe extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%company}}', [
            'user_id' => Schema::TYPE_PK . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
        ]);
        $this->addForeignKey('fk_company_user', '{{%company}}','user_id', '{{%user}}', 'id');
        $this->createIndex('idx_company_user', '{{%company}}', 'user_id');
        $this->createTable('{{%subscribe}}', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING . '(128) NOT NULL', 
            'phone' => Schema::TYPE_STRING . '(32)',
            'user_id' => Schema::TYPE_INTEGER,
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%subscribe}}');
        $this->dropIndex('idx_company_user', '{{%company}}');
        $this->dropForeignKey('fk_company_user', '{{%company}}');
        $this->dropTable('{{%company}}');
    }
}
