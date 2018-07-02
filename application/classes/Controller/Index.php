<?php

class Controller_Index extends Controller{

    /**
     * 首页入口
     * @author yz
     */
    public function action_index(){
        echo View::factory('index');
    }

    //end function
}