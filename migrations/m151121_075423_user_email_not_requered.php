<?php

use yii\db\Migration;
use yii\db\Schema;

class m151121_075423_user_email_not_requered extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%user%}}', 'email', Schema::TYPE_STRING . '(255)');
        
    }

    public function safeDown()
    {
        $this->alterColumn('{{%user%}}', 'email', Schema::TYPE_STRING . '(255) NOT NULL');
    }
}
