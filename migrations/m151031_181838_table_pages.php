<?php

use yii\db\Migration;
use yii\db\Schema;

class m151031_181838_table_pages extends Migration
{
 
    public function safeUp()
    {
        $this->createTable('{{%pages}}', [
            'id' => Schema::TYPE_PK . ' NOT NULL',
            'company_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'url' => Schema::TYPE_STRING . '(150) NOT NULL',
            'meta_description' => Schema::TYPE_TEXT . ' NOT NULL',
            'title' => Schema::TYPE_STRING . '(250) NOT NULL',
            'meta_keyword' => Schema::TYPE_STRING . '(250) NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL'
        ]);
        $this->addForeignKey('fk_page_company', '{{%pages}}', 'company_id', '{{%company}}', 'user_id');
        $this->createIndex('idx_page_url', '{{%pages}}', 'url');
    }

    public function safeDown()
    {
        $this->dropIndex('idx_page_url', '{{%pages}}');
        $this->dropForeignKey('fk_page_company', '{{%pages}}');
        $this->dropTable('{{%pages}}');
    }
}
