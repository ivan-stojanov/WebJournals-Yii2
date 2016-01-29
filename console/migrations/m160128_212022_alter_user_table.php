<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_212022_alter_user_table extends Migration
{
	public function up()
	{
		$this->alterColumn('user', 'last_login', 'int(11) DEFAULT NULL');		
	}

	public function down()
	{
		$this->alterColumn('user', 'last_login', 'datetime DEFAULT NULL');
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
