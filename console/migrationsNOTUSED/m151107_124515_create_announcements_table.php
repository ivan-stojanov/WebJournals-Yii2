<?php

use yii\db\Schema;
use yii\db\Migration;

class m151107_124515_create_announcements_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('announcement', array(
			'announcement_id'		=> 'pk',				
			'title'   				=> 'varchar(255)',
			'description'			=> 'varchar(255)',
			'content'				=> 'text',
			'sort_order'			=> 'int(11) NOT NULL',
			'is_visible'			=> 'boolean DEFAULT true',
			'created_on'   			=> 'datetime',
			'updated_on'   			=> 'datetime',
			'is_deleted'    		=> 'boolean DEFAULT false',
		), $tableOptions);
	}

	public function down()
	{
		$this->dropTable('announcement');
	}
}
