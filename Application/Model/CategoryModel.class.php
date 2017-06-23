<?php

/**
 * Created by PhpStorm.
 * User: ydm5-01
 * Date: 2017/4/2
 * Time: 11:34
 */
class CategoryModel extends Model
{
    /**
     * 获取所有的分类数据
     * @param int $parent_id
     * @return array
     */
    public function getList($parent_id = 0){
        //1.准备sql
            $sql = "select * from category";
        //2.执行sql
            $rows = $this->db->fetchAll($sql);
            //排序和缩进
            $rows = $this->getChildren($rows,$parent_id,0);//帮我实现排序和缩进
        //3.返回结果
            return $rows;
    }

    /**
     * 找儿子的方法
     * @param $rows 需要排序的数据
     * @param $deep 深度
     * @param $parent_id 起始节点的父id
     */
    private function getChildren(&$rows,$parent_id,$deep=0){
        static $children = [];//保存的所有的儿子
        foreach ($rows as $child){
            if($child['parent_id'] == $parent_id){
                $child['name_txt'] = str_repeat('&emsp;',$deep*3).$child['name'];//已经加上空格的字符串
                $children[] = $child;//节点AAA
                //在继续找节点AAA的儿子
                $shendu = $deep+1;
                $this->getChildren($rows,$child['id'],$shendu);
            }
        }
        return $children;
    }

    /**
     * 添加商品分类
     * @param $data
     */
    public function add($data){
        //1.商品分类名称不能为空
        if(empty($data['name'])){
            $this->error = "商品分类名不能为空！";
            return false;
        }
        //2.同级分类，分类名称不能重复
            $sql_2 = "select count(*) from category WHERE parent_id={$data['parent_id']} and name = '{$data['name']}'";
            $count = $this->db->fetchColumn($sql_2);
            if($count>0){
                $this->error = "同级分类名称重复";
                return false;
            }
        //1.准备sql
            $sql = "insert into category VALUES (null,'{$data['name']}','{$data['intro']}',{$data['parent_id']})";
        //2.执行sql
            $result = $this->db->query($sql);
        //3.返回结果
            return $result;
    }

    /**
     * 根据id删除一行数据
     * @param $id
     */
    public function delete($id){
        //判断当前分类下是否有子分类，如果有就不能删除
            $sql_2 = "select count(*) from category WHERE parent_id={$id}";
            $count = $this->db->fetchColumn($sql_2);
            if($count > 0){
                $this->error = "当前分类下有子类，不能直接删除！";
                return false;
            }
        //1.准备sql
            $sql = "delete from category WHERE id={$id}";
        //2.执行sql
            $result = $this->db->query($sql);
        //3.返回结果
            return $result;
    }

    /**
     * 根据id查询出一条数据
     * @param $id
     */
    public function getOne($id){
        //1.准备sql
        $sql = "select * from category WHERE id={$id}";
        //2.执行sql
        $row = $this->db->fetchRow($sql);
        //3.返回结果
        return $row;
    }

    /**
     * 根据id更新数据
     * @param $data 包含id的数组
     */
    public function update($data){
        //1.商品分类名不能为空
            if(empty($data['name'])){
                $this->error = "商品分类名称不能为空！";
                return false;
            }
        //2.修改后不能和同级分类的其他分类重名，可以和自己重名
            $sql_1 = "select count(*) from category WHERE parent_id={$data['parent_id']} and name='{$data['name']}' and id <> {$data['id']}";
            if($this->db->fetchColumn($sql_1) > 0){
                $this->error = "修改后不能和同级分类的其他分类重名！";
                return false;
            }
        //3.不能修改到自己分类下面和子孙分类下面
            //就是说明 ， parent_id 不能等于 自己的id 和 子孙分类的id
            $ids = [];//保存自己id 和 子孙分类id

            //获取子孙分类的id
            $children = $this->getList($data['id']);
            $ids = array_column($children,'id');
            $ids[] = $data['id'];
        
            if(in_array($data['parent_id'],$ids)){
                $this->error = "不能修改到自己分类下面和子孙分类下面！";
                return false;
            }
        //1.准备sql
            $sql = "update category set name='{$data['name']}',parent_id={$data['parent_id']},intro='{$data['intro']}' WHERE id={$data['id']}";
        //2.执行sql
            $result = $this->db->query($sql);
        //3.返回结果
            return $result;
    }
}