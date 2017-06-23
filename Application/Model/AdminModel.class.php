<?php

//require FRAME_PATH.'Model.class.php';

class AdminModel extends Model{

    //获取所有数据
    public function getAll(){
        //准备sql
        $sql = "select * from admin";
        $rows = $this->db->fetchAll($sql);
        return $rows;
    }
    //完成删除
    public function delete($id){
        //1.准备sql
        $sql = "delete from admin WHERE id={$id}";
        //2.执行sql
        $this->db->query($sql);
    }

    /**
     * 验证用户名和密码是否正确
     * @param $username
     * @param $password
     */
    public function check($username,$password){

        $username = $this->db->escape_sq($username);
        $password = $this->db->escape_sq($password);

        //1.将传入的密码进行md5加密
        $password = md5($password);

        //2.到数据库中查询是否有对应得用户和密码
        $sql = "select * from admin WHERE username='{$username}' and password='{$password}' limit 1";

        $row = $this->db->fetchRow($sql);
        if(empty($row)){
            $this->error = "用户名或者密码错误！";
            return false;
        }else{
            return $row;
        }
    }

    /**
     * 验证cookie中的id和password
     * @param $id
     * @param $password
     */
    public function checkByCookie($id,$password){
        //1.根据id到数据库中查询对应得用户信息
            $sql = "select * from admin WHERE id={$id} limit 1";
            $row = $this->db->fetchRow($sql);
        //2.将用户信息中的password取出来，再次加密，与传入的进行比对
            if(empty($row)){
                return false;
            }
            $password_in_db = $row['password'];
            $password_in_db = md5($password_in_db."_itsource");
            if($password == $password_in_db){
                return $row;
            }else{
                return false;
            }
    }
}