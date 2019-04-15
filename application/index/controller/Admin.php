<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-02
 * Time: 23:44
 */

namespace app\index\controller;

use think\Config;
use think\Session;

/**
 * Class Admin 管理员
 * @package app\index\controller
 */
class Admin
{
    /**
     * 登陆
     * @return \think\response\Json
     */
    public function login(){
        Config::set("default_return_type","json");
        $int = model("LogicPerson","logic")->isAdminByTel(input("tel"),input("password"));
        if ($int==1){
            Session::set("admin","admin");
            return json(array("description"=>"OK"),200);
        }else if ($int==0){
            return json(array("description"=>"error", "detail"=>"password error"),400);
        }else{
            return json(array("description"=>"error", "detail"=>"not null"),400);
        }
    }

    /**
     * 下线 删除session
     * @return \think\response\Json
     */
    public function offline(){
        Config::set("default_return_type","json");
        Session::delete("admin");
        return json(array("description"=>"OK"),200);
    }

    /**
     * 首页 json数据 ！！！！未完
     * @return \think\response\Json
     */
    public function homePage(){
        Config::set("default_return_type","json");
        if (Session::get("admin")=="admin"){
            $hotels = model("Hotel","logic")->findAllHotel();
            $allHotel = count($hotels);//全部店家数
            $examineHotel = 0;//审核店家
            $onlineHotel = 0;//上线店家
            foreach ($hotels as $key=>$hotel){
                if ($hotel->examine==true)
                    $examineHotel++;
                if ($hotel->online==true)
                    $onlineHotel++;
            }
            /**
             * allHotel:12,//全部店家
             * examineHotel:5,已经审核店家
             * onlineHotel:8,以上线店家
             * order:120,订单数
             * user:1000,用户数
             * successOrder:110成功订单
             * failureOrder:2失败订单
             */
            $data=array(
                'allHotel'=>$allHotel,
                'examineHotel'=>$examineHotel,
                'onlineHotel'=>$onlineHotel,
                'order'=>120,
                'user'=>1000,
                'successOrder'=>110,
                'failureOrder'=> 2
            );
            return json(array("description"=>"OK","data"=>$data),200);
        }else{
            return json(array("description"=>"error", "detail"=>"not login"),400);
        }
    }

    /**
     * 待审核页面数据 待审核店家
     */
    public function reviewPage(){
        Config::set("default_return_type","json");
        $hotels = model("Hotel","logic")->findNotAuditHotels();
        return  json(array("description"=>"OK","data"=>$hotels),200);
    }

    /**
     * 审核
     * 成功返回ok + 未审核数据
     */
    public function audit(){
        Config::set("default_return_type","json");
        $tel = input("tel");
        $int = model("Hotel","logic")->auditHotel($tel);
        if ($int!=1){
            return json(array("description"=>"error", "detail"=>"error"),400);
        }else{
            $hotels = model("Hotel","logic")->findNotAuditHotels();
            return  json(array("description"=>"OK","data"=>$hotels),200);
        }
    }

    /**
     * 待上线页面数据 待上线店家
     * @return \think\response\Json
     */
    public function onlinePage(){
        Config::set("default_return_type","json");
        $hotels = model("Hotel","logic")->findNotOnline();
        return  json(array("description"=>"OK","data"=>$hotels),200);
    }

    /**
     * 管理员未上线数据
     * @return \think\response\Json
     */
    public function online(){
        Config::set("default_return_type","json");
        $tel = input("tel");
        $int = model("Hotel","logic")->onlineHotel($tel);
        if ($int!=1){
            return json(array("description"=>"error", "detail"=>"error"),400);
        }else{
            $hotels = model("Hotel","logic")->findNotOnline();
            return  json(array("description"=>"OK","data"=>$hotels),200);
        }
    }
}