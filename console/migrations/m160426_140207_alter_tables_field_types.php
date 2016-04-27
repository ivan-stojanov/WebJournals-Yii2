<?php

use yii\db\Migration;

class m160426_140207_alter_tables_field_types extends Migration
{
    public function up()
    {
    	$this->alterColumn('issue', 'published_on', 'datetime');
    }

    public function down()
    {
        $this->alterColumn('issue', 'published_on', 'date');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
