<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_140516_create_authors_table extends Migration
{
	public function up()
	{
		$this->createTable('author', array(
			'author_id'    	=> 'pk',
			'first_name' 	=> 'varchar(40) NOT NULL',
			'middle_name' 	=> 'varchar(40)',
			'last_name' 	=> 'varchar(90) NOT NULL',
			'sufix' 		=> 'varchar(40)',
			'country' 		=> 'varchar(90)',
			'email' 		=> 'varchar(90) NOT NULL',
			'url' 			=> 'varchar(255)',
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',
			'is_deleted'    => 'boolean DEFAULT false',
		));
	}

	public function down()
	{
		$this->dropTable('author');
	}
}
