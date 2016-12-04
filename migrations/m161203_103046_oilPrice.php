<?php

use yii\db\Migration;

class m161203_103046_oilPrice extends Migration
{
    public function up()
    {
        // "TRADEDATE", "SECID", "OPEN", "LOW", "HIGH", "CLOSE"
        $this->createTable('{{%oil_price}}', 
            [
                'id' => $this->primaryKey(),
                'tradedate' => $this->date(),
                'code' => $this->string(4),
                'close_price' => $this->float(),
                'open_price' => $this->float(),
                'low_price' => $this->float(),
                'high_price' => $this->float(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%oil_price}}');
    }
}
