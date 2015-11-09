<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_140653_create_keywords_table extends Migration
{
	public function up()
	{
		$this->createTable('keyword', array(
			'keyword_id'    => 'pk',
			'content' 		=> 'text NOT NULL',
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',			
			'is_deleted'    => 'boolean DEFAULT false',
		));
	}

	public function down()
	{
		$this->dropTable('keyword');
	}
}
