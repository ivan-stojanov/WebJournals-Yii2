<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_144229_create_sections_table extends Migration
{
	public function up()
	{
		$this->createTable('section', array(
			'section_id'    => 'pk',
			'issue_id'    	=> 'int(11) NOT NULL',
			'title'    		=> 'text NOT NULL',			
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',
			'is_deleted'    => 'boolean DEFAULT false',
		));
		
		$this->addForeignKey(
			'fk_article_section',
			'article', 'section_id',
			'section', 'section_id',
			'CASCADE',
			'CASCADE');	
	}

	public function down()
	{
		$this->dropForeignKey('fk_article_section', 'article');
		$this->dropTable('section');
	}
}