<?php

/**
 * 登录控制器
 * Class LoginController
 */
class LoginController extends Controller
{
    public function login(){
        //1.接收参数
        //2.处理数据
        //3.显示页面
        $this->display('login');
    }

    /**
     * 验证登录
     */
    public function check(){
        @session_start();
        //验证 验证码
        $captcha = $_POST['captcha'];
        /**
         * 将验证 和生成的 随机字符串都转化成小写比对，不区分大小写
         */
        if(strtolower($captcha) != strtolower($_SESSION['random_code'])){
            $this->redirect("index.php?p=Admin&c=Login&a=login","验证码错误！",3);
        }

        //1.接收参数
        $username = $_POST['username'];
        $password = $_POST['password'];
        //2.处理数据
        $adminModel = new AdminModel();
        /*
        * 验证失败 返回 false 成功时 返回用户信息的数组
        */
        $result = $adminModel->check($username,$password);
        //3.显示页面
        if($result === false){
            $this->redirect('index.php?p=Admin&c=Login&a=login',$adminModel->getError(),3);
        }else{//登录成功
            //保存登录信息到cookie
//            setcookie('isLogin','yes');

            //工作中用户登录信息放入session
//            session_start();
//            $_SESSION['isLogin'] = "yes";

            //工作中是将用户信息保存到session

            $_SESSION['USER_INFO'] = $result;


            if(isset($_POST['remember'])){//如果点击了记住登录
                //保存id和密码 信息到cookie中
                setcookie('id',$result['id'],time()+ 7*24*3600,'/');
                //需要对password进行处理，再次加密
                $password = md5($result['password']."_itsource");
                setcookie('password',$password,time()+7*24*3600,'/');
            }
            $this->redirect('index.php?p=Admin&c=Index&a=index');
        }
    }

    /**
     * 注销
     */
    public function logout(){
        //将登录相关的信息删除
        //不能删除cookie中的PHPSESSID

        //删除session中的用户信息
            @session_start();
            unset($_SESSION['USER_INFO']);
        //删除cookie中的id和password
            setcookie('id',null,-1,'/');//一定要写上路径
            setcookie('password',null,-1,'/');

        //跳转到登录页面
            $this->redirect('index.php?p=Admin&c=Login&a=login',"注销成功！",3);
    }
}