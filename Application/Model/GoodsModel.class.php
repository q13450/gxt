<?php

/**
 * Created by PhpStorm.
 * User: ydm5-01
 * Date: 2017/4/11
 * Time: 14:17
 */
class GoodsModel extends Model
{
    /**
     * 根据条件获取数据
     * @param string $condition
     * @return array
     */
    public function getAll($condition=''){
        //1.准备sql
            $sql = "select * from goods WHERE 1=1";
            if(!empty($condition)){
                $sql .= " and ".$condition;
            }
        //2.执行sql
            $rows = $this->db->fetchAll($sql);
            //将商品状态拆分成三个状态
            foreach($rows  as &$row){
                $row['is_best'] = ($row['status'] & 1) > 0 ? 1 : 0;
                $row['is_new'] = ($row['status'] & 2) > 0 ? 1 : 0;
                $row['is_hot'] = ($row['status'] & 4) > 0 ? 1 : 0;
            }
        //3.返回结果
            return $rows;
    }
    /**
     * 添加商品
     * @param $data
     */
    public function add($data){
        //1.准备sql
            //处理状态 每一个状态相 或 |
            $status = 0;//默认 为 零
            if(isset($data['status'])){
                foreach($data['status'] as $v){
                    $status = $status | $v;
                }
            }
            //处理时间
            $add_time = time();

            $sql = "insert into goods(`name`,sn,category_id,shop_price,market_price,logo,thumb_logo,intro,num,status,is_on_sale,add_time) VALUES ('{$data['name']}','{$data['sn']}',{$data['category_id']},{$data['shop_price']},{$data['market_price']},'{$data['logo']}','{$data['thumb_logo']}','{$data['intro']}',{$data['num']},$status,{$data['is_on_sale']},{$add_time})";
        //2.执行sql
            $result = $this->db->query($sql);
        //3.返回结果
            return $result;
    }

    /**
     * 获取分页所需要的所有数据
     * 完成分页，需要的数据
     *  1.当前页的所有数据
     *  2.总条数
     *  3.每页显示条数
     *  4.当前页码
     *  5.总页数
     *
     * 通过数组的形式返回
     */
    public function getPage($page,$pageSize){
        //获取总条数
        $sql_count = "select count(*) from goods limit 1";
        $count = $this->db->fetchColumn($sql_count);

        //获取总页数
        $totalPage = ceil($count/$pageSize);//ceil 向上取整

        //获取当前页的数据
        $page = $page > $totalPage ? $totalPage : $page;

        $start = ($page-1)*$pageSize;
        $start = $start <0 ? 0 : $start;
        $sql_rows = "select * from goods order by id asc limit {$start},{$pageSize}";
        $rows = $this->db->fetchAll($sql_rows);

        foreach($rows  as &$row){
            $row['is_best'] = ($row['status'] & 1) > 0 ? 1 : 0;
            $row['is_new'] = ($row['status'] & 2) > 0 ? 1 : 0;
            $row['is_hot'] = ($row['status'] & 4) > 0 ? 1 : 0;
        }

        return ['rows'=>$rows,'count'=>$count,'pageSize'=>$pageSize,'page'=>$page,'totalPage'=>$totalPage];
    }
}