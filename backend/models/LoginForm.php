<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\models;

use common\models\LoginForm as commonLoginForm;

/**
 * Description of LoginForm
 *
 * @author Vladimir
 */
class LoginForm extends commonLoginForm
{

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        $user = parent::getUser();
        if ($user && $user->validateAdmin()) {
            return $user;
        } else {
            return null;
        }
    }
}
