<?php

use yii\db\Migration;

class m170107_193758_alter_article_table extends Migration
{
    public function up()
    {
    	$this->alterColumn('article', 'section_id', 'int(11) DEFAULT NULL');
    	$this->dropColumn('article', 'is_archived');
    	$this->addColumn('article', 'status', 'INT NOT NULL DEFAULT 0 COMMENT "0 - submitted; 1 - under review; 2 - review required; 3 - accepted for publication; 4 - published; 5 - rejected;" AFTER sort_in_section');
    }

    public function down()
    {
    	$this->alterColumn('article', 'section_id', 'int(11) NOT NULL');
    	$this->addColumn('article', 'is_archived', 'boolean DEFAULT false AFTER sort_in_section');
    	$this->dropColumn('article', 'status');
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
