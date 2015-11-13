<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_141146_create_articleAuthors_and_articleKeywords_relation_tables extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('article_author', array(
			'article_id'    	=> 'int(11) NOT NULL',
			'author_id'    		=> 'int(11) NOT NULL',
			'sort_order'	  	=> 'int(11) NOT NULL',
			'is_correspondent'	=> 'boolean DEFAULT false',
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			"PRIMARY KEY (`article_id`, `author_id`)",
		), $tableOptions);
		
		$this->addForeignKey(
			'fk_article_author_article',
			'article_author', 'article_id',
			'article', 'article_id',
			'CASCADE',
			'CASCADE');

		$this->addForeignKey(
			'fk_article_author_author',
			'article_author', 'author_id',
			'author', 'author_id',
			'CASCADE',
			'CASCADE');
			
		
		$this->createTable('article_keyword', array(
			'article_id'    => 'int(11) NOT NULL',
			'keyword_id'   	=> 'int(11) NOT NULL',
			'order'		    => 'int(11) NOT NULL',
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',			
			"PRIMARY KEY (`article_id`, `keyword_id`)",
		), $tableOptions);
		
		$this->addForeignKey(
			'fk_article_keyword_article',
			'article_keyword', 'article_id',
			'article', 'article_id',
			'CASCADE',
			'CASCADE');

		$this->addForeignKey(
			'fk_article_keyword_keyword',
			'article_keyword', 'keyword_id',
			'keyword', 'keyword_id',
			'CASCADE',
			'CASCADE');	
	}

	public function down()
	{
		$this->dropForeignKey('fk_article_author_article', 'article_author');
		$this->dropForeignKey('fk_article_author_author', 'article_author');		
		$this->dropTable('article_author');
		
		$this->dropForeignKey('fk_article_keyword_article', 'article_keyword');
		$this->dropForeignKey('fk_article_keyword_keyword', 'article_keyword');		
		$this->dropTable('article_keyword');		
	}
}
