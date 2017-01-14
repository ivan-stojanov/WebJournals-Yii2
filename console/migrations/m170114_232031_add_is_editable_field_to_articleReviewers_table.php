<?php

use yii\db\Migration;

class m170114_232031_add_is_editable_field_to_articleReviewers_table extends Migration
{
	public function up()
	{
		$this->addColumn('article_reviewer', 'is_editable', 'boolean DEFAULT true AFTER is_submited');
	}

	public function down()
	{
		$this->dropColumn('article_reviewer', 'is_editable');
	}
}
