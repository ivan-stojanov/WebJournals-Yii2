<?php

use yii\db\Migration;

class m170107_215657_create_articleEditors_relation_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('article_editor', array(
			'article_id'    	=> 'int(11) NOT NULL',
			'editor_id'    		=> 'int(11) NOT NULL',
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			"PRIMARY KEY (`article_id`, `editor_id`)",
		), $tableOptions);
		
		$this->addForeignKey(
			'fk_article_editor_article',
			'article_editor', 'article_id',
			'article', 'article_id',
			'CASCADE',
			'CASCADE');

		$this->addForeignKey(
			'fk_article_editor_user',
			'article_editor', 'editor_id',
			'user', 'id',
			'CASCADE',
			'CASCADE');	
	}

	public function down()
	{
		$this->dropForeignKey('fk_article_editor_article', 'article_editor');
		$this->dropForeignKey('fk_article_editor_user', 'article_editor');		
		$this->dropTable('article_editor');
	}
}
