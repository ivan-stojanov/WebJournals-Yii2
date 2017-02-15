<?php

use yii\db\Migration;

class m170213_212231_fix_comments_for_article_column_status extends Migration
{
	public function up()
	{
		$this->alterColumn('article', 'status', 'INT NOT NULL DEFAULT 0 COMMENT "0 - submitted; 1 - under review; 2 - review required; 3 - improvement; 4 - accepted for publication; 5 - published; 6 - rejected;" AFTER sort_in_section');
	}
	
	public function down()
	{
		$this->alterColumn('article', 'status', 'INT NOT NULL DEFAULT 0 COMMENT "0 - submitted; 1 - under review; 2 - review required; 3 - accepted for publication; 4 - published; 5 - rejected;" AFTER sort_in_section');
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
