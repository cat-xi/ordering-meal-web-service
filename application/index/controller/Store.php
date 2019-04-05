<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-05
 * Time: 17:37
 */

namespace app\index\controller;

use app\common\pojo\Dish;
use app\common\pojo\Hotel;
use think\Config;
use think\Session;
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
            return 'OK!';
        }else if ($int ==-1){
            return "不为null";
        }else{
            return "error";
        }
    }
    /**
     * 显示店家自己数据页面，判断登陆 session
     * @return \think\response\Json name=>名称 tel=>电话 password=>密码 location=>位置 location=>位置 cuisine=>菜系 examine=>审核 online=>上线 portrait=>头像 orderCount=>订单数 state=>状态
     */
    public function oneself(){
        Config::set("default_return_type","json");
        $tel = Session::get('hotel');
        if ($tel==null){
            return json(array("description"=>"error", "detail"=>"not login"),400);
        }else{
            $data = model("Hotel","logic")->findHotelByTel($tel);
            $newData = array(
                'name'=>$data->name,
                'tel'=>$data->tel,
                'password'=>$data->password,
                'location'=>$data->location,
                'cuisine'=>$data->cuisine,
                'examine'=>$data->examine,
                'online'=>$data->online,
                'portrait'=>$data->portrait,
                'orderCount'=>'133',
                'state'=>'上线',
            );
            return  json(array("description"=>"OK","data"=>$newData),200);
        }
    }

    /**
     * 登陆 添加session
     */
    public function login(){
//        return "登陆成功";
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

    /**
     * 添加菜单
     * 判断null 判断图片
     * 成功返回新菜单
     */
    public function uploadMenu(){
        Config::set("default_return_type","json");
        $tel = Session::get("hotel");
        $arr = input('menu');
        $list = json_decode($arr,true);
        $dishes=[];
        for ($i=0;$i<count($list);$i++){
//            echo $list[$i]['name'];

            if (empty($_FILES["$i"]['tmp_name'])){
                return json(array("description"=>"error", "detail"=>"data error"),400);
            }else{
//                $img = file_get_contents(iconv('gb2312','utf-8',$_FILES["$i"]['tmp_name']));
            }
            if ($list["$i"]['name']==null||$list["$i"]['price']==null)
                return json(array("description"=>"error", "detail"=>"data error"),400);
            array_push($dishes,new Dish(null,$tel,$list["$i"]['name'],$list["$i"]['price'],file_get_contents(iconv('gb2312','utf-8',$_FILES["$i"]['tmp_name']))));
        }
        $int = model("LogicDish","logic")->addDishes($dishes);
        if ($int==0){
            return json(array("description"=>"error", "detail"=>"data error"),400);
        }else{
            return json(array("description"=>"OK","data"=>model("LogicDish","logic")->findDishesByTel($tel)),200);
        }
    }

    /**
     * 显示店家菜单
     * @return \think\response\Json
     */
    public function menu(){
        Config::set("default_return_type","json");
        $tel = Session::get('hotel');
        return  json(array("description"=>"OK","data"=>model("LogicDish","logic")->findDishesByTel($tel)),200);
    }

    public function dish($id){
        $file = model("LogicDish","logic")->findImageById($id);
        header('content-type:image');
        echo $file;
    }
}