<?php

use yii\db\Migration;

class m170219_163212_add_columns_in_article_table extends Migration
{
	public function up()
	{
		$this->addColumn('article', 'is_archived', 'boolean DEFAULT false AFTER file_id');
		$this->addColumn('article', 'send_emails', 'boolean DEFAULT true AFTER is_archived');
	}
	
	public function down()
	{
		$this->dropColumn('article', 'is_archived');
		$this->dropColumn('article', 'send_emails');
	}
	
}
