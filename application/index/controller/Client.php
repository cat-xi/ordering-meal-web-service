<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-06
 * Time: 14:26
 */

namespace app\index\controller;


use app\common\pojo\Order;
use app\common\pojo\Person;
use think\Config;
use think\Session;

class Client
{
    /**
     * 用户注册
     * @return \think\response\Json
     */
    public function register(){
        Config::set("default_return_type","json");
        $int = model("LogicPerson","logic")->addClient(new Person(input("tel"),input("password"),null));
        if ($int==1){
            return json(array("description"=>"OK"),200);
        }else if ($int==-1){
            return json(array("description"=>"error", "detail"=>"not null"),400);
        }else{
            return json(array("description"=>"error", "detail"=>"error"),400);
        }
    }

    /**
     * 用户登陆
     * @return \think\response\Json
     */
    public function login(){
        Config::set("default_return_type","json");
        $tel = input("tel");
        $int = model("LogicPerson","logic")->login(new Person($tel,input("password"),null));
        if ($int==1){
            //成功设置session
            Session::set("client","$tel");
            return json(array("description"=>"OK"),200);
        }else if ($int==-1){
            return json(array("description"=>"error", "detail"=>"not null"),400);
        }else{
            return json(array("description"=>"error", "detail"=>"password error"),400);
        }
    }

    /**
     * 判断是否登陆 登陆ok
     * @return \think\response\Json
     */
    public function isLogin(){
        Config::set("default_return_type","json");
        if (Session::get("client")==null)
            return json(array("description"=>"error", "detail"=>"not login"),400);
        return json(array("description"=>"OK"),200);
    }

    /**
     * 用户下线 删除session
     * @return \think\response\Json
     */
    public function offline(){
        Config::set("default_return_type","json");
        Session::delete("client");
        return json(array("description"=>"OK"),200);
    }

    /**
     * 查询全部店家
     * @return \think\response\Json
     */
    public function findHotels(){
        Config::set("default_return_type","json");
        $hotels = model("Hotel","logic")->findOnlineHotels();
        return json(array("description"=>"OK","data"=>$hotels),200);
    }

    /**
     * 根据店家查询店家及菜单
     * 在菜单内加入数量
     */
    public function findHotelByTel(){
        Config::set("default_return_type","json");
        $tel = input("tel");
        $hotel = model("Hotel","logic")->findHotelByTel(input("tel"));
        $menu = model('LogicDish','logic')->findDishesByTel($tel);
        $newMenu=[];
//        public $id;//id
//        public $tel;//店家id
//        public $name;//菜名
//        public $price;//价格
//        public $picture;//图片
        foreach ($menu as $dish){
            array_push($newMenu,array(
                'id'=>$dish->id,
                'name'=>$dish->name,
                'price'=>$dish->price,
                'picture'=>$dish->picture,
                'count'=>0
            ));
        }
        $data=array(
            'hotel'=>$hotel,
            'menu'=>$newMenu
        );
        //判断是否登陆
        if (Session::get("client")==null)
            return json(array("description"=>"error", "detail"=>"not login"),400);
        return json(array("description"=>"OK","data"=>$data),200);
    }

    /**
     * 创建订单，生成订单，加入session
     * 用户订餐界面结算方法
     * @return \think\response\Json
     */
    public function makeOrder(){
        Config::set("default_return_type","json");
        $menu = input('menu');
        $list = json_decode($menu,true);
        $money = input('money');
        $tel = input('tel');
        $newMenu=[];
        foreach ($list as $dish){
            if ($dish['count']>0){
                array_push($newMenu,[
                    'id'=>$dish['id'],
                    'name'=>$dish['name'],
                    'price'=>$dish['price'],
                    'count'=>$dish['count'],
                ]);
            }
        }
        $order = new Order(Session::get("client"),$tel,$newMenu,$money,null,-1);
        Session::set('order',$order);
        return json(array("description"=>"OK"),200);
    }

    /**
     * 获取当前用户订单信息
     * @return \think\response\Json
     */
    public function getOrder(){
        Config::set("default_return_type","json");
        //判断是否登陆
        if (Session::get("client")==null)
            return json(array("description"=>"error", "detail"=>"not login"),400);
        //判断是否有订单生成
        if (Session::get('order')==null)
            return json(array("description"=>"error", "detail"=>"not make order"),400);
        return json(array("description"=>"OK","data"=>Session::get('order')),200);
    }

    /**
     * 结算,提交订单
     * @return \think\response\Json
     */
    public function placeOrder(){
        Config::set("default_return_type","json");
        $order = Session::get('order');
        $data = new Order($order->client,$order->hotel,$order->menu,$order->money,input('address'),$order->condition);
        model("ModelOrder","model")->insertOrder($data);
        return json(array("description"=>"OK"),200);
    }
}