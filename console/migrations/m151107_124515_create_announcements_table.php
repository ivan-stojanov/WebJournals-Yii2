<?php

use yii\db\Schema;
use yii\db\Migration;

class m151107_124515_create_announcements_table extends Migration
{
	public function up()
	{
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
		));
	}

	public function down()
	{
		$this->dropTable('announcement');
	}
}
