<?php

use yii\db\Schema;
use yii\db\Migration;

class m160319_190840_alter_tables_add_sort extends Migration
{
    public function up()
    {
    	$this->addColumn('volume', 'sort_in_system', 'int(11) DEFAULT 0 AFTER year');
    	$this->addColumn('issue', 'sort_in_volume', 'int(11) DEFAULT 0 AFTER cover_image');
    	$this->addColumn('section', 'sort_in_issue', 'int(11) DEFAULT 0 AFTER title');
    	$this->addColumn('article', 'sort_in_section', 'int(11) DEFAULT 0 AFTER page_to');
    }

    public function down()
    {
        $this->dropColumn('volume', 'sort_in_system');
        $this->dropColumn('issue', 'sort_in_volume');
        $this->dropColumn('section', 'sort_in_issue');
        $this->dropColumn('article', 'sort_in_section');
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
