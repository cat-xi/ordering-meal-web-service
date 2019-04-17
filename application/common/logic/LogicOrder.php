<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-16
 * Time: 14:11
 */

namespace app\common\logic;


use app\common\pojo\Order;
use think\Model;

class LogicOrder extends Model
{
    /**
     * 添加订单
     * @param Order $order
     * @return int 失败返回0 否则返回1
     */
    public function addOrder(Order $order){
        $bool = \model("ModelOrder","model")->insertOrder($order);
        if ($bool==false)
            return 0;
        return 1;
    }

    /**
     * 根据店家查找订单
     * @param $hotel
     * @return mixed
     */
    public function findOrdersByHotel($hotel){
        $data = \model("ModelOrder",'model')->selectOrdersByHotel($hotel);
        return $data;
    }

    /**
     * 根据用户查找订单
     * @param $client
     * @return mixed
     */
    public function findOrdersByClient($client){
        $data = \model("ModelOrder",'model')->selectOrdersByClient($client);
        return $data;
    }

    /**
     * 店家接单
     * @param $id int 订单id
     * @return mixed
     */
    public function shopkeeperOrders($id){
        $order = new Order($id,null,null,null,null,null,1);
        return \model("ModelOrder",'model')->updateOrder($order);
    }

    /**
     * 用户确认订单 完成订单
     * @param $id int 订单id
     * @return mixed
     */
    public function confirmOrder($id){
        return \model("ModelOrder",'model')->updateOrder(new Order($id,null,null,null,null,null,2));
    }

    /**
     * 全部订单信息 ['全部订单数','成功订单数','未完成订单数']
     * @return array
     */
    public function orderMessage(){
        $arr=[];
        $orders = \model('ModelOrder','model')->selectAllOrders();
        $successOrder = 0;//成功订单
        foreach ($orders as $order){
            if ($order->condition==2)
                $successOrder++;
        }
        $count = count($orders);//全部订单
        $failureOrder = $count-$successOrder;//未完成订单
        $arr+=[$count,$successOrder,$failureOrder];
        return $arr;
    }
}