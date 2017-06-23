<?php

//require './Model/Model.class.php';


class BrandModel extends Model
{
    //获取所有数据
    public function getAll(){
        //准备sql
        $sql = "select * from brand";
        //执行sql
        $rows = $this->db->fetchAll($sql);


        foreach($rows as $key=>$row){
            $rows[$key]['is_best'] = ($row['status'] & 8) > 0 ? 1:0;
            $rows[$key]['is_new'] = ($row['status'] & 4) > 0 ? 1:0;
            $rows[$key]['is_hot'] = ($row['status'] & 2) > 0 ? 1:0;
            $rows[$key]['is_die'] = ($row['status'] & 1) > 0 ? 1:0;
        }
        return $rows;
    }
    //完成删除
    public function delete($id){
        //1.准备sql
        $sql = "delete from brand WHERE id={$id}";
        //2.执行sql
        $this->db->query($sql);
    }
}