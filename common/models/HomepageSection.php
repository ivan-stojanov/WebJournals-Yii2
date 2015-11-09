<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "homepage_section".
 *
 * @property integer $homepage_section_id
 * @property integer $sort_order
 * @property string $section_type
 * @property string $section_content
 * @property integer $is_visible
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 */
class HomepageSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'homepage_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order'], 'required'],
            [['sort_order', 'is_visible', 'is_deleted'], 'integer'],
            [['section_content'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['section_type'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'homepage_section_id' => 'Homepage Section ID',
            'sort_order' => 'Sort Order',
            'section_type' => 'Section Type',
            'section_content' => 'Homepage Section Content',
            'is_visible' => 'Is Visible',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
    	$query = HomepageSection::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    
    	$this->load($params);
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to return any records when validation fails
    		// $query->where('0=1');
    		return $dataProvider;
    	}
    
    	$query->andFilterWhere([
    			'homepage_section_id' => $this->homepage_section_id,
    			'sort_order' => $this->sort_order,
    			'is_visible' => $this->is_visible,
    			'created_on' => $this->created_on,
    			'updated_on' => $this->updated_on,
    			'is_deleted' => $this->is_deleted,
    	]);
    
    	$query->andFilterWhere(['like', 'section_type', $this->section_type])
    	->andFilterWhere(['like', 'section_content', $this->section_content]);
    
    	return $dataProvider;
    }
}
