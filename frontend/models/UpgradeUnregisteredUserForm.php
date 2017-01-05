<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class UpgradeUnregisteredUserForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Helper token cannot be blank.');
        }
        $this->_user = User::findByHelperTokenForUpgrade($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong helper token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function upgradeUnregisteredUser()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->is_unregistered_author = false;
        $user->helper_token = null;

        return $user->save(false);
    }
}
