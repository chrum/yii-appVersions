<?php

class m140916_122804_addVersionsTable extends CDbMigration
{
    public function up()
    {
        $this->createTable('{{versions}}', array(
            'id' => 'pk',
            'version' => 'int NOT NULL',
            'message' => 'text NULL',
            'platforms' => 'varchar(64) NULL'
        ));
    }


    public function down()
    {
        $this->dropTable('{{versions}}');
    }
}