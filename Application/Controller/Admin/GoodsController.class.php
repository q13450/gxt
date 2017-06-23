<?php

/**
 *
 */
class GoodsController extends PlatformController
{
    /**
     * 商品列表页
     */
    public function index(){
        //1.接收数据
            $condition = [];//组装条件
            if(!empty($_POST['category_id'])){
                $condition[] = "category_id = {$_POST['category_id']}";
            }
            //判断对应得状态值
            if(!empty($_POST['status'])){
                $condition[] = "(status & {$_POST['status']}) > 0";
            }

            //判断是否上架
            if(!empty($_POST['is_on_sale'])){
                $condition[] = "is_on_sale = {$_POST['is_on_sale']}-1";
            }

            //判断关键字
            if(!empty($_POST['keyword'])){
                $condition[] = "(name like '%{$_POST['keyword']}%' or sn like '%{$_POST['keyword']}%')";
            }

            //将数组转化成字符串
            $condition = implode(' and ',$condition);
        //2.处理数据
            //a.获取商品所有数据
            $goodsModel = new GoodsModel();
            $list = $goodsModel->getAll($condition);
            //分配商品数据
            $this->assign('list',$list);


            //b.获取商品分类数据
            $categoryModel = new CategoryModel();
            $categorys = $categoryModel->getList();

            $this->assign('categorys',$categorys);
        //3.显示页面
        $this->display('index');
    }

    /**
     * 分页
     */
    public function page(){
        //1.接收数据

        //2.处理数据
        //a.获取商品所有数据
        $goodsModel = new GoodsModel();
        /**
         * 完成分页，需要的数据
         *  1.当前页的所有数据
         *  2.总条数
         *  3.每页显示条数
         *  4.当前页码
         *  5.总页数
         */
        $page = isset($_GET['page']) ? $_GET['page']:1;
        $pageSize = 2;
        $pageResult = $goodsModel->getPage($page,$pageSize);

        //分配商品数据
        $this->assign($pageResult);

        //b.获取商品分类数据
        $categoryModel = new CategoryModel();
        $categorys = $categoryModel->getList();

        $this->assign('categorys',$categorys);
        //3.显示页面
        $this->display('page');
    }

    /**
     * 完成添加
     */
    public function add(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            //1.接收数据
                $data = $_POST;

                //处理文件上传
                $uploadModel = new UploadModel();
                /**
                 * 成功 返回图片路径 失败 返回false
                 */
                $logo_path = $uploadModel->upload($_FILES['logo'],'goods/');
                if($logo_path === false){
                    $this->redirect('index.php?p=Admin&c=Goods&a=add',$uploadModel->getError(),3);
                }else{
                    $data['logo'] = $logo_path;

                    //只有当商品图片上传成功后才制作缩略图，因为缩略需要一张原图
                    $imageModel = new ImageModel();
                    /**
                     * 期望该方法，成功返回缩略图的路径，失败返回 false
                     */
                    $thumb_logo = $imageModel->thumb($logo_path,80,80);
                    if($thumb_logo === false){
                        $this->redirect('index.php?p=Admin&c=Goods&a=add',$imageModel->getError(),3);
                    }else{
                        $data['thumb_logo'] = $thumb_logo;
                    }
                }
            //2.处理数据
                $goodsModel = new GoodsModel();
                $goodsModel->add($data);
            //3.显示页面
                $this->redirect('index.php?p=Admin&c=Goods&a=index');
        }else{
            //1.接收数据
            //2.处理数据
                //获取商品分类数据
                $categoryModel = new CategoryModel();
                $categorys = $categoryModel->getList();

                //分配分类数据到页面
                $this->assign("categorys",$categorys);
            //3.显示页面
            $this->display('add');
        }
    }
}