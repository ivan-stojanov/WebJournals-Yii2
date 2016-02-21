<?php

use yii\db\Schema;
use yii\db\Migration;

class m160221_105802_create_articleReviewers_relation_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('article_reviewer', array(
			'article_id'    	=> 'int(11) NOT NULL',
			'reviewer_id'    	=> 'int(11) NOT NULL',
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			"PRIMARY KEY (`article_id`, `reviewer_id`)",
		), $tableOptions);
		
		$this->addForeignKey(
			'fk_article_reviewer_article',
			'article_reviewer', 'article_id',
			'article', 'article_id',
			'CASCADE',
			'CASCADE');

		$this->addForeignKey(
			'fk_article_reviewer_user',
			'article_reviewer', 'reviewer_id',
			'user', 'id',
			'CASCADE',
			'CASCADE');	
	}

	public function down()
	{
		$this->dropForeignKey('fk_article_reviewer_article', 'article_reviewer');
		$this->dropForeignKey('fk_article_reviewer_user', 'article_reviewer');		
		$this->dropTable('article_reviewer');
	}
}
