<?php

use yii\db\Schema;
use yii\db\Migration;

class m160221_224815_create_images_table extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('image', array(
			'image_id'    	=> 'pk',
			'path'    		=> 'varchar(1024)',
			'type'    		=> 'text NOT NULL',
			'name'      	=> 'text NOT NULL',			
			'size'    		=> 'int(11) NOT NULL',		
			'created_on'   	=> 'datetime',
			'updated_on'   	=> 'datetime',
			'is_deleted'    => 'boolean DEFAULT false',
		), $tableOptions);
		
		$this->alterColumn('issue', 'cover_image', 'int(11)');
		
		$this->addForeignKey(
				'fk_issue_image',
				'issue', 'cover_image',
				'image', 'image_id',
				'CASCADE',
				'CASCADE');
	}

	public function down()
	{
		$this->dropForeignKey('fk_issue_image', 'issue');
		$this->alterColumn('issue', 'cover_image', 'varchar(255)');
		$this->dropTable('image');
	}
}
