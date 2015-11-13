<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_144229_create_sections_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('section', array(
			'section_id'    => 'pk',
			'issue_id'    	=> 'int(11) NOT NULL',
			'title'    		=> 'text NOT NULL',			
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',
			'is_deleted'    => 'boolean DEFAULT false',
		), $tableOptions);
		
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