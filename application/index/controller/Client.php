<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-06
 * Time: 14:26
 */

namespace app\index\controller;


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
}