<?php

use yii\db\Migration;

class m161119_173827_add_columns_in_article_reviewer_table extends Migration
{
	public function up()
	{
		$this->addColumn('article_reviewer', 'short_comment', 'INT NOT NULL DEFAULT 0 COMMENT "0 - none; 1 - accept(minor change); 2 - accept(major change); 3 - reject;" AFTER reviewer_id');
		$this->addColumn('article_reviewer', 'long_comment', 'text AFTER short_comment');
		$this->addColumn('article_reviewer', 'is_submited', 'boolean DEFAULT false AFTER long_comment');
		$this->renameColumn('article_keyword', 'order', 'sort_order');
	}

	public function down()
	{
		$this->dropColumn('article_reviewer', 'is_submited');
		$this->dropColumn('article_reviewer', 'long_comment');
		$this->dropColumn('article_reviewer', 'short_comment');
		$this->renameColumn('article_keyword', 'sort_order', 'order');
	}

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
