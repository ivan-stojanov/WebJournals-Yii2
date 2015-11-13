<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_140653_create_keywords_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('keyword', array(
			'keyword_id'    => 'pk',
			'content' 		=> 'text NOT NULL',
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',			
			'is_deleted'    => 'boolean DEFAULT false',
		), $tableOptions);
	}

	public function down()
	{
		$this->dropTable('keyword');
	}
}
