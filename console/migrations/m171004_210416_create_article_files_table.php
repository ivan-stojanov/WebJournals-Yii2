<?php

use yii\db\Migration;

class m171004_210416_create_article_files_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		 
		$this->createTable('article_files', [
				'file_id' 			=> 'pk',
				'user_id' 			=> 'int(11) NOT NULL',
				'article_id' 		=> 'int(11) NOT NULL',
				'file_name' 		=> 'tinytext',
				'file_original_name'=> 'tinytext',
				'file_mime_type' 	=> 'tinytext',
				'created_on' 		=> 'datetime',
				'is_deleted'    	=> 'boolean DEFAULT false',
		], $tableOptions);
	
		$this->addForeignKey(
				'fk_article_files_user',
				'article_files', 'user_id',
				'user', 'id',
				'CASCADE',
				'CASCADE');
		
		$this->addForeignKey(
				'fk_article_files_article',
				'article_files', 'article_id',
				'article', 'article_id',
				'CASCADE',
				'CASCADE');		
	}
	
	public function down()
	{
		$this->dropForeignKey('fk_article_files_article', 'article_files');
		$this->dropForeignKey('fk_article_files_user', 'article_files');
		$this->dropTable('article_files');
	}
}
