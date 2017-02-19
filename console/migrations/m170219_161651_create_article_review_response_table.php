<?php

use yii\db\Migration;

class m170219_161651_create_article_review_response_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
        	// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
        	$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('article_review_response', [
        		'article_review_response_id' => 'pk',
        		'article_id' 			=> 'int(11) NOT NULL',
        		'reviewer_id' 			=> 'int(11) NOT NULL',
        		'response_creator_id' 	=> 'int(11) NOT NULL',
         		'long_comment' 			=> 'varchar(100) DEFAULT NULL',
        		'created_on'   			=> 'datetime',
        		'updated_on'   			=> 'datetime',
        		'is_deleted'    		=> 'boolean DEFAULT false',
        ], $tableOptions);
        
        $this->addForeignKey(
        		'fk_article_review_response__article',
        		'article_review_response', 'article_id',
        		'article', 'article_id',
        		'CASCADE',
        		'CASCADE');
        
        $this->addForeignKey(
        		'fk_article_review_response__user_as_reviewer',
        		'article_review_response', 'reviewer_id',
        		'user', 'id',
        		'CASCADE',
        		'CASCADE');
        
        $this->addForeignKey(
        		'fk_article_review_response__user_as_responser',
        		'article_review_response', 'response_creator_id',
        		'user', 'id',
        		'CASCADE',
        		'CASCADE');
    }

    public function down()
    {
    	$this->dropForeignKey('fk_article_review_response__article', 'article_review_response');
    	$this->dropForeignKey('fk_article_review_response__user_as_reviewer', 'article_review_response');
    	$this->dropForeignKey('fk_article_review_response__user_as_responser', 'article_review_response');
    	$this->dropTable('article_review_response');
    }
    
}
