<?php

use yii\db\Migration;

class m160625_185703_add_is_current_flag_in_issues_table extends Migration
{
	public function up()
	{
		$this->addColumn('issue', 'is_current', 'boolean DEFAULT false AFTER sort_in_volume');
	}

	public function down()
	{
		$this->dropColumn('issue', 'is_current');
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
