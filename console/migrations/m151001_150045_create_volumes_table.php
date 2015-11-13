<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_150045_create_volumes_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('volume', array(
			'volume_id'    		=> 'pk',
			'title'    			=> 'text NOT NULL',	
			'year'    			=> 'varchar(10)',	
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			'is_deleted'    	=> 'boolean DEFAULT false',
		), $tableOptions);
		
		$this->addForeignKey(
			'fk_volume_issue',
			'issue', 'volume_id',
			'volume', 'volume_id',
			'CASCADE',
			'CASCADE');	
	}

	public function down()
	{
		$this->dropForeignKey('fk_volume_issue', 'issue');
		$this->dropTable('volume');
	}
}
