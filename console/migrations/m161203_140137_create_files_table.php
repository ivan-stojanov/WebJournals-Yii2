<?php

use yii\db\Migration;

class m161203_140137_create_files_table extends Migration
{
    public function up()
    {
    	$tableOptions = null;
    	if ($this->db->driverName === 'mysql') {
    		// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    	}
    	
        $this->createTable('article_file', [
            	'file_id' 			=> 'pk',
            	'user_id' 			=> 'int(11) NOT NULL',
        		'file_name' 		=> 'tinytext',
        		'file_original_name'=> 'tinytext',
           	 	'file_mime_type' 	=> 'tinytext',            	
            	'created_on' 		=> 'datetime',
        		'is_deleted'    	=> 'boolean DEFAULT false',
        ], $tableOptions);
        
        $this->addForeignKey(
        		'fk_article_file_user',
        		'article_file', 'user_id',
        		'user', 'id',
        		'CASCADE',
        		'CASCADE');
        
        $this->addColumn('article', 'is_archived', 'boolean DEFAULT false AFTER sort_in_section');
        $this->addColumn('article', 'file_id', 'int(11) AFTER is_archived');
        
        $this->addForeignKey(
        		'fk_file__article_file',
        		'article', 'file_id',
        		'article_file', 'file_id',
        		'CASCADE',
        		'CASCADE');
    }

    public function down()
    {
    	$this->dropForeignKey('fk_file__article_file', 'article');
    	$this->dropColumn('article', 'file_id');
    	$this->dropColumn('article', 'is_archived');
    	$this->dropForeignKey('fk_article_file_user', 'article_file');
        $this->dropTable('article_file');
    }
}
