<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_135229_create_articles_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('article', array(
			'article_id'    => 'pk',
			'section_id'    => 'int(11) NOT NULL',
			'title'    		=> 'text NOT NULL',
			'abstract'      => 'text NOT NULL',
			'content'      	=> 'text NOT NULL',
			'pdf_content' 	=> 'text',
			'page_from'    	=> 'varchar(6)',
			'page_to'   	=> 'varchar(6)',
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',
			'is_deleted'    => 'boolean DEFAULT false',
		), $tableOptions);
	}

	public function down()
	{
		$this->dropTable('article');
	}
}
