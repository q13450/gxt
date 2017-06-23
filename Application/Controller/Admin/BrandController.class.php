<?php

/**
 * Created by PhpStorm.
 * User: ydm5-01
 * Date: 2017/4/5
 * Time: 14:16
 */
class BrandController extends PlatformController
{


    public function index(){
        //1.接收数据
        //2.处理数据
//        require './Model/BrandModel.class.php';
        $brandModel = new BrandModel();
        $rows = $brandModel->getAll();//获取所有数据在页面展示
//        $name = "张三";
//        $age = 18;
        $userinfo = ['name'=>"张三",'age'=>18];//一维的关联数组
        $this->assign($userinfo);
        //3.显示页面
        $this->assign('rows',$rows);
//        $this->assign('name',$name);
//        $this->assign('age',$age);

        $this->display('index');//希望当前类上有个方法可以帮我加载视图文件
    }



    public function remove(){
        //1.接收数据
        $id = $_GET['id'];
        //2.处理数据
//        require './Model/BrandModel.class.php';
        $brandModel = new BrandModel();
        $brandModel->delete($id);
        //3.显示
        $this->redirect('index.php?p=Admin&c=Brand&a=index','xxx',4);
    }
}