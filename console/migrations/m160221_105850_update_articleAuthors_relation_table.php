<?php

use yii\db\Schema;
use yii\db\Migration;

class m160221_105850_update_articleAuthors_relation_table extends Migration
{
	public function up()
	{
		/*$this->dropForeignKey('fk_article_author_author', 'article_author');*/
		
		$this->addForeignKey(
				'fk_article_author_user',
				'article_author', 'author_id',
				'user', 'id',
				'CASCADE',
				'CASCADE');
	}

	public function down()
	{
		$this->dropForeignKey('fk_article_author_user', 'article_author');
		
		/*$this->addForeignKey(
				'fk_article_author_author',
				'article_author', 'author_id',
				'author', 'author_id',
				'CASCADE',
				'CASCADE');*/
	}
}
