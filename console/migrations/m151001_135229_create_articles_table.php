<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_135229_create_articles_table extends Migration
{
	public function up()
	{
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
		));
	}

	public function down()
	{
		$this->dropTable('article');
	}
}
