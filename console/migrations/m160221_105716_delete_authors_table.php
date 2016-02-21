<?php

use yii\db\Schema;
use yii\db\Migration;

class m160221_105716_delete_authors_table extends Migration
{
	public function up()
	{
		$this->dropForeignKey('fk_article_author_author', 'article_author');
		$this->dropTable('author');
	}
	
	public function down()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('author', array(
			'author_id'    		=> 'pk',
			'first_name' 		=> 'varchar(100) NOT NULL',
			'middle_name' 		=> 'varchar(100)',
			'last_name' 		=> 'varchar(100) NOT NULL',
			'email' 			=> 'varchar(90) NOT NULL',
			'title' 			=> 'varchar(255)',
			'affiliation' 		=> 'text',				
			'gender' 			=> 'ENUM("Male", "Female", "Other")',
			'mailing_address'	=> 'text',
			'country' 			=> 'varchar(100)',			
			'url' 				=> 'varchar(255)',
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			'is_deleted'    	=> 'boolean DEFAULT false',
		), $tableOptions);
		
		$this->addForeignKey(
				'fk_article_author_author',
				'article_author', 'author_id',
				'author', 'author_id',
				'CASCADE',
				'CASCADE');
	}
}
