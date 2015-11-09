<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_150045_create_volumes_table extends Migration
{
	public function up()
	{
		$this->createTable('volume', array(
			'volume_id'    		=> 'pk',
			'title'    			=> 'text NOT NULL',	
			'year'    			=> 'varchar(10)',	
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			'is_deleted'    	=> 'boolean DEFAULT false',
		));
		
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
