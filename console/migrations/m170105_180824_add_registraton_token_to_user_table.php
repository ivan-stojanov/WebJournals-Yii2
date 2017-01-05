<?php

use yii\db\Migration;

class m170105_180824_add_registraton_token_to_user_table extends Migration
{
    public function up()
    {
    	$this->addColumn('user', 'registration_token', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
    	$this->dropColumn('user', 'registration_token');
    }
}
