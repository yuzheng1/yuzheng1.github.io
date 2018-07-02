<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Rest extends Kohana_Controller_Rest {

    /**
     * 设置认证类型
     * @var unknown
     */
    protected $_auth_type = RestUser::AUTH_TYPE_OFF;

    /**
     * 验证请求
     */
    public function authorization($client_id, $client_secret) {
        if ($client_id == 'xiechengtest' || $client_id == '114piaowutest' ||$client_id == 'tongchengtest' ||$client_id == 'tuniutest') {
            return FALSE;
        }
        $authorization = md5($client_id . md5($client_id));
        if ($client_secret == $authorization) {
            return TRUE;
        } else {
            //验证失败，输出错误信息给客户端
            return FALSE;
        }
    }

}
