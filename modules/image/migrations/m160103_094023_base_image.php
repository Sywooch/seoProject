<?php

use yii\db\Migration;

class m160103_094023_base_image extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('{{%image_item}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(128)->notNull(),
            'description' => $this->text(),
            'created_at' => $this->integer(),
            'update_at' => $this->integer(),
        ]);
        $this->addForeignKey('fk_image_item_user', '{{%image_item}}', 'user_id', '{{%user}}', 'id');
        $this->createIndex('idx_image_item_user', '{{%image_item}}', 'user_id');
        $this->createTable('{{%areas}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128),
            'item_id' => $this->integer(),
//            'area' => 'GEOMETRY(POLYGON, 4326),'
        ]);
        $sql = "SELECT AddGeometryColumn( 'areas', 'area', 4326, 'POLYGON', 2);";
        $this->execute($sql);
        $this->addForeignKey('fk_areas_item', '{{%areas}}', 'item_id', '{{%image_item}}', 'id');
        $this->createIndex('idx_area_item', '{{%areas}}', 'item_id');
    }

    public function safeDown()
    {
        $this->dropIndex('idx_area_item', '{{%areas}}');
        $this->dropForeignKey('fk_areas_item', '{{%areas}}');
        $this->dropTable('{{%areas}}');
        $this->dropIndex('idx_image_item_user', '{{%image_item}}');
        $this->dropForeignKey('fk_image_item_user', '{{%image_item}}');
        $this->dropTable('{{%image_item}}');
    }

}
