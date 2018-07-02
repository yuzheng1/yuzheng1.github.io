<?php defined('SYSPATH') or die('No direct script access.');

class RestUser extends Kohana_RestUser {
    /**
     * 根据token，返回用户信息
     * (non-PHPdoc)
     * @see Kohana_RestUser::_find()
     */
    protected function _find(){
        $auth_key = $this->_api_key;
        if($auth_key){
            //获取用户信息
            $key = param::get($auth_key);
            $user= new Sr_Sso_Handle();
            $user  = $user->getUserInfo($key,'65b105e0a19184c8edb82c56b9ebf991');
            if($user){
                $this->_id = $user->id;
            }
        }
        //用户未登录。
    }

}
