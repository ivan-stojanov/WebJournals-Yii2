<?php

/* 
 * This model was copied from the following site:
 * https://github.com/wbraganca/yii2-dynamicform
 */

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

class DynamicForms extends \yii\base\Model
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @param array $data
     * @return array
     */
    public static function createMultiple($modelClass, $key = 'id', $multipleModels = [], $data = null)//
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        // added $data=null to function arguments
        // modified the following line to accept new argument
        $post     = empty($data) ? Yii::$app->request->post($formName) : $data[$formName];
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, $key, $key));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item[$key]) && !empty($item[$key]) && isset($multipleModels[$item[$key]])) {
                    $models[] = $multipleModels[$item[$key]];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }
}