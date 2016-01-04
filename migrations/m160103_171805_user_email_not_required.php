<?php

use yii\db\Migration;
use yii\db\Schema;
class m160103_171805_user_email_not_required extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%user}}', 'email');
        $this->addColumn('{{%user}}', 'email', $this->string(128));
    }

    public function down()
    {
//        $this->alterColumn('{{%user}}', 'email', Schema::TYPE_STRING . ' NOT NULL');
    }
}
