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
     * 店家注册
     * @return \think\response\Json
     */
    public function register(){
        Config::set("default_return_type","json");
        if (empty($_FILES["portrait"]['tmp_name'])){
            return json(array("description"=>"error", "detail"=>"not input"),400);
        }
        $image = file_get_contents(iconv('gb2312','utf-8',$_FILES["portrait"]['tmp_name']));
        $json = json_decode(input('menu'),true);
        $hotel = new Hotel($json[0]['name'],$json[0]['tel'],$json[0]['password'],$json[0]['location'],$json[0]['cuisine'],null,null,null,$image);
        $int = model("Hotel","logic")->registered($hotel);
        if ($int==1){
            return json(array("description"=>"OK"),200);
        }else{
            return json(array("description"=>"error", "detail"=>"data error"),400);
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
            $int = $data->menu+$data->online+$data->examine;
            $state='';
            if ($int==3){
                $state='已上线';
            }else if($int==2){
                $state='待上线';
            }else if ($int==1){
                $state='待上传菜单';
            }else{
                $state='待审核';
            }
            $newData = array(
                'name'=>$data->name,
                'tel'=>$data->tel,
                'password'=>$data->password,
                'location'=>$data->location,
                'cuisine'=>$data->cuisine,
                'examine'=>$data->examine,
                'menu'=>$data->menu,
                'online'=>$data->online,
                'portrait'=>$data->portrait,
                'orderCount'=>model('Hotel','logic')->orderCountByTel($data->tel),
                'state'=>$state,
            );
            return  json(array("description"=>"OK","data"=>$newData),200);
        }
    }

    /**
     * 登陆 添加session
     */
    public function login(){
        Config::set("default_return_type","json");
        $tel = input('tel');
        $int = model("Hotel","logic")->login(new Hotel(null,$tel,input('password'),null,null,null,null,null,null));
        if ($int==1){
            Session::set("hotel","$tel");
            return json(array("description"=>"OK"),200);
        }else if ($int==-1){
            return json(array("description"=>"error", "detail"=>"not null"),400);
        }else{
            return json(array("description"=>"error", "detail"=>"password error"),400);
        }
    }

    /**
     * 下线 删除session
     */
    public function offline(){
        Config::set("default_return_type","json");
        Session::delete("hotel");
        return json(array("description"=>"OK"),200);
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
     * 判断是否已经审核!!!!!!
     */
    public function uploadMenu(){
        Config::set("default_return_type","json");
        $tel = Session::get("hotel");
        $arr = input('menu');
        $list = json_decode($arr,true);
        $dishes=[];
        //判断是否已经审核
        if (model("Hotel","logic")->findHotelByTel($tel)->examine!=true)
            return json(array("description"=>"error", "detail"=>"not examine"),400);
        for ($i=0;$i<count($list);$i++){
            if (empty($_FILES["$i"]['tmp_name'])){
                return json(array("description"=>"error", "detail"=>"data error"),400);
            }
            if ($list["$i"]['name']==null||$list["$i"]['price']==null)
                return json(array("description"=>"error", "detail"=>"data error"),400);
            array_push($dishes,new Dish(null,$tel,$list["$i"]['name'],$list["$i"]['price'],file_get_contents(iconv('gb2312','utf-8',$_FILES["$i"]['tmp_name']))));
        }
        $int = model("LogicDish","logic")->addDishes($dishes);
        if ($int==0){//判断提交菜单
            return json(array("description"=>"error", "detail"=>"data error"),400);
        }else{
            //店家状态更改 menu为true online为false
            model("Hotel","logic")->uploadMenu($tel);
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

    /**
     * 显示菜品图片
     * @param $id int 菜品id
     */
    public function dish($id){
        $file = model("LogicDish","logic")->findImageById($id);
        header('content-type:image');
        echo $file;
    }

    /**
     * 查询当前店家的订单
     * @return \think\response\Json
     */
    public function findOrders(){
        Config::set("default_return_type","json");
        $tel = Session::get('hotel');
        if ($tel==null)
            return json(array("description"=>"error", "detail"=>"not login"),400);
        return  json(array("description"=>"OK","data"=>model('LogicOrder','logic')->findOrdersByHotel($tel)),200);
    }

    /**
     * 店家接单
     * @return \think\response\Json
     */
    public function shopkeeperOrders(){
        Config::set("default_return_type","json");
        $int = input("id");
        $tel = Session::get('hotel');
        if ($int==null)
            return json(array("description"=>"error", "detail"=>"not null"),400);
        model('LogicOrder','logic')->shopkeeperOrders($int);
        return  json(array("description"=>"OK","data"=>model('LogicOrder','logic')->findOrdersByHotel($tel)),200);
    }
}