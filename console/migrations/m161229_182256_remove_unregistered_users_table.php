<?php

use yii\db\Migration;

class m161229_182256_remove_unregistered_users_table extends Migration
{
    public function up()
    {
    	//reverse last migration
    	$this->dropForeignKey('fk_unregistered_user__user', 'unregistered_user');
    	$this->dropTable('unregistered_user');
    	
    	//apply new flow for unregistered users
    	$this->addColumn('user', 'is_unregistered_author', 'boolean DEFAULT false AFTER is_reviewer');
    	$this->addColumn('user', 'creator_user_id', 'int(11) AFTER is_unregistered_author');
    	$this->addColumn('user', 'helper_token', 'varchar(255) DEFAULT NULL');
    	$this->addForeignKey(
	    			'fk_user__user',
	    			'user', 'creator_user_id',
	    			'user', 'id',
	    			'CASCADE',
	    			'CASCADE');
    	//make some fields nullabe
    	$this->alterColumn('user', 'auth_key', 'varchar(32) DEFAULT NULL');
    	$this->alterColumn('user', 'password_hash', 'varchar(255) DEFAULT NULL');
    	$this->alterColumn('user', 'password_reset_token', 'varchar(255) DEFAULT NULL');
    	$this->alterColumn('user', 'created_at', 'int(11) DEFAULT NULL');
    	$this->alterColumn('user', 'updated_at', 'int(11) DEFAULT NULL');
    }

    public function down()
    {
    	//reverse last migration
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
        	'affiliation'   	=> 'text DEFAULT NULL',
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
        
        //apply new flow for unregistered users
        $this->dropForeignKey('fk_user__user', 'user');
        $this->dropColumn('user', 'creator_user_id');
        $this->dropColumn('user', 'is_unregistered_author');
        $this->dropColumn('user', 'helper_token');
        //make some fields not nullabe
        $this->alterColumn('user', 'auth_key', 'varchar(32) NOT NULL');
        $this->alterColumn('user', 'password_hash', 'varchar(255) NOT NULL');
        $this->alterColumn('user', 'password_reset_token', 'varchar(255) NOT NULL');
        $this->alterColumn('user', 'created_at', 'int(11) NOT NULL');
        $this->alterColumn('user', 'updated_at', 'int(11) NOT NULL');
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
