<?php

use yii\db\Migration;

class m161204_193120_alter_issue_special_editor_filed extends Migration
{
    public function up()
    {
    	$this->alterColumn('issue', 'special_editor', 'int(11) DEFAULT NULL');    	
    	$this->addForeignKey(
    			'fk_issue__special_editor',
    			'issue', 'special_editor',
    			'user', 'id',
    			'CASCADE',
    			'CASCADE');
    }

    public function down()
    {
    	$this->dropForeignKey('fk_issue__special_editor', 'issue');
		$this->alterColumn('issue', 'special_editor', 'varchar(255)');
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
