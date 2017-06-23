<?php


class IndexController extends PlatformController
{

    public function index(){
        //1.接收参数
        //2.处理数据
        //3.显示页面
        $this->display('index');
    }

    public function top(){
        @session_start();

        //1.接收参数
        //2.处理数据
        //3.显示页面
        $this->assign($_SESSION['USER_INFO']);
        $this->display('top');
    }
    public function menu(){
        //验证登录信息
        //1.接收参数
        //2.处理数据
        //3.显示页面
        $this->display('menu');
    }
    public function main(){
        //1.接收参数
        //2.处理数据
        //3.显示页面
        $this->display('main');
    }
}