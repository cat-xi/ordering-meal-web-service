<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-02
 * Time: 10:33
 */

namespace app\index\controller;

use app\common\pojo\Hotel;
use think\Config;
use think\Session;

/**
 * Class Store 店家
 * @package app\index\controller
 */
class Store
{
    /**
     * 全部店家
     * @return mixed
     */
    public function index()
    {
        Config::set("default_return_type","json");
        $hotels = model("Hotel","logic")->findAllHotel();
        return $hotels;
    }

    /**
     * 注册
     */
    public function register(){
        $image = file_get_contents($_FILES['portrait']['tmp_name']);
        $int = model("Hotel","logic")->registered(new Hotel(input('name'),input('tel'),input('password'),input('location'),input('cuisine'),null,null,$image));
        if ($int==1){
            echo 'OK!';
        }else if ($int ==-1){
            echo "不为null";
        }else{
            echo "error";
        }
    }

    /**
     * 显示店家自己数据页面，判断登陆 session
     */
    public function oneself(){
        Config::set("default_return_type","json");
        $tel = Session::get('hotel');
        if ($tel==null){
            return '没有登陆';
        }else{
            return model("Hotel","logic")->findHotelByTel($tel);
        }
    }

    /**
     * 登陆 添加session
     */
    public function login(){
        $tel = input('tel');
        $int = model("Hotel","logic")->login(new Hotel(null,$tel,input('password'),null,null,null,null,null));
        if ($int==1){
            Session::set("hotel","$tel");
            echo "登陆成功";
        }else if ($int==-1){
            echo "内容为null";
        }else{
            echo "密码错误";
        }
    }

    /**
     * 下线 删除session
     */
    public function offline(){
        Session::delete("hotel");
    }

    /**
     * 根据tel显示店家图片
     * @param $id int 店家id
     */
    public function show($id){
        $file = model("Hotel","logic")->findImageByTel($id);
        header('content-type:image');
        echo $file;
    }


}