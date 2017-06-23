<?php

class AdminController extends PlatformController
{
    /**
     * 完成列表功能
     */
    public function index(){
        //1.处理数据
//        require MODEL_PATH.'AdminModel.class.php';
        $adminModel = new AdminModel();
        $rows = $adminModel->getAll();
        /////逻辑处理部分///////////
        $this->assign('rows',$rows);
        /////////数据显示部分///////////
//        require CURRENT_VIEW_PATH."index.html";
        $this->display('index');
    }
    /**
     * 完成删除功能
     */
    public function remove(){
        //1.接收数据
        $id = $_GET['id'];
        //2.处理数据
//        require MODEL_PATH.'AdminModel.class.php';
        $adminModel = new AdminModel();
        $adminModel->delete($id);
        //3.显示
        header("Location: index.php?p=Admin&c=Admin&a=index");
    }
}