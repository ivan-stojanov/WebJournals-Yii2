<?php

use yii\db\Schema;
use yii\db\Migration;

class m151106_115553_create_homepage_sections_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('homepage_section', array(
			'homepage_section_id'	=> 'pk',	
			'section_type'   		=> 'ENUM("page_content", "current_issue", "other")',
			'section_content'		=> 'text',
			'section_url'   		=> 'varchar(255)',
			'sort_order'			=> 'int(11) NOT NULL',
			'is_visible'			=> 'boolean DEFAULT false',			
			'created_on'   			=> 'datetime',
			'updated_on'   			=> 'datetime',
			'is_deleted'    		=> 'boolean DEFAULT false',
		), $tableOptions);
		
		$this->insert('homepage_section', array(
			'sort_order'        	=> 1,
			'section_type'     		=> 'page_content',
			'is_visible'			=> false,
			'created_on'   			=> date("Y-m-d H:i:s"),
		));
		
		$this->insert('homepage_section', array(
			'sort_order'        	=> 2,
			'section_type'     		=> 'current_issue',
			'is_visible'			=> false,
			'created_on'   			=> date("Y-m-d H:i:s"),
		));
	}

	public function down()
	{
		$this->delete('homepage_section', 'section_type = \'current_issue\'');
		$this->delete('homepage_section', 'section_type = \'page_content\'');
		$this->dropTable('homepage_section');
	}
}
