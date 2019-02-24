<?php

use yii\db\Migration;

class m161225_134243_create_unregistered_users_table extends Migration
{
    public function up()
    {
    	$tableOptions = null;
    	if ($this->db->driverName === 'mysql') {
    		// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    	}
    	
        $this->createTable('unregistered_user', [
            'unregistered_user_id' => 'pk',
        	'user_creator_id' 	=> 'int(11) NOT NULL',
            'username' 			=> $this->string()->notNull()->unique(),
            'email' 			=> $this->string()->notNull()->unique(),
        	'first_name' 		=> 'varchar(100) DEFAULT NULL',
        	'last_name' 		=> 'varchar(100) DEFAULT NULL',        		
        	'middle_name' 		=> 'varchar(100) DEFAULT NULL',
        	'gender'			=> 'ENUM("Male", "Female", "Other") DEFAULT NULL',
        	'initials'			=> 'varchar(10) DEFAULT NULL',
        	'mailing_address'	=> 'text DEFAULT NULL',
        	'country'			=> 'varchar(100) DEFAULT NULL',
			'created_on'   		=> 'datetime',
			'updated_on'   		=> 'datetime',
			'is_deleted'    	=> 'boolean DEFAULT false',
        ], $tableOptions);
        
        $this->addForeignKey(
        		'fk_unregistered_user__user',
        		'unregistered_user', 'user_creator_id',
        		'user', 'id',
        		'CASCADE',
        		'CASCADE');        
    }

    public function down()
    {
    	$this->dropForeignKey('fk_unregistered_user__user', 'unregistered_user');    	 
        $this->dropTable('unregistered_user');
    }
}
