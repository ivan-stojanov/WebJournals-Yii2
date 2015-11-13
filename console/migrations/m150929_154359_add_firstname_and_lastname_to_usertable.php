<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_154359_add_firstname_and_lastname_to_usertable extends Migration
{
	public function up()
	{
		$this->addColumn('user', 'first_name', 'varchar(100) DEFAULT NULL');
		$this->addColumn('user', 'last_name', 'varchar(100) DEFAULT NULL');
	}

	public function down()
	{
		$this->dropColumn('user', 'first_name');
		$this->dropColumn('user', 'last_name');
	}
}
