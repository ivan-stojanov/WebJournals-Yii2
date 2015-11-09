<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_144810_create_issues_table extends Migration
{
	public function up()
	{
		$this->createTable('issue', array(
			'issue_id'    		=> 'pk',
			'volume_id'    		=> 'int(11) NOT NULL',
			'title'    			=> 'text NOT NULL',	
			'published_on'  	=> 'date',
			'is_special_issue'	=> 'boolean DEFAULT false',
			'special_title'    	=> 'text',
			'special_editor'   	=> 'varchar(255)', //this line may be change in the future
			'cover_image'		=> 'varchar(255)',
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			'is_deleted'    	=> 'boolean DEFAULT false',
		));
		
		$this->addForeignKey(
			'fk_section_issue',
			'section', 'issue_id',
			'issue', 'issue_id',
			'CASCADE',
			'CASCADE');	
	}

	public function down()
	{
		$this->dropForeignKey('fk_section_issue', 'section');
		$this->dropTable('issue');
	}
}
