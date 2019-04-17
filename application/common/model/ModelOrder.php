<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-16
 * Time: 11:41
 */

namespace app\common\model;


use app\common\pojo\Order;
use think\Model;

/**
 * Class ModelOrder 数据层
 * @package app\common\model
 */
class ModelOrder extends Model
{
    /**
     * 添加订单 事务处理
     * @param $order Order 订单对象
     * @return mixed false失败
     */
    public function insertOrder($order){
        return $this->transaction(function () use ($order) {
            //订单id
            $id = $this->table('orders')->insert(array(
                    'client'=>$order->client,
                    'hotel'=>$order->hotel,
                    'money'=>$order->money,
                    'address'=>$order->address,
                    'condition'=>$order->condition
                ),false,true,'id'
            );
            //菜单
            $menu=[];
            foreach ($order->menu as $dish){
                array_push($menu,array(
                    'order_id'=>$id,
                    'name'=>$dish['name'],
                    'price'=>$dish['price'],
                    'count'=>$dish['count']
                ));
            }
            $this->table('order_menu')->insertAll($menu);
        });
    }


    /**
     * 更新订单
     * @param Order $order
     * @return
     */
    public function updateOrder(Order $order){
        $data=[];
        if ($order->id==null)
            return -1;
        if ($order->hotel!=null)
            $data+=array('hotel'=>$order->hotel);
        if ($order->money!=null)
            $data+=array('money'=>$order->money);
        if ($order->address!=null)
            $data+=array('address'=>$order->address);
        if ($order->client!=null)
            $data+=array('client'=>$order->client);
        if ($order->condition!=null)
            $data+=array('condition'=>$order->condition);
        $data += array('id'=>$order->id);
        return $this->table('orders')->where('id',$order->id)->update($data);
    }

    /**
     * 根据店家查找订单
     * @param $hotel string 店家id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectOrdersByHotel($hotel){
        $listOrders = $this->table('orders')->where('hotel',$hotel)->field('id,client,hotel,money,address,condition')->select();
        $orders=[];
        foreach ($listOrders as $order){
            $menu = $this->table('order_menu')->where('order_id',$order['id'])->field('name,price,count')->select();
            array_push($orders,new Order($order['id'],$order['client'],$order['hotel'],$menu,$order['money'],$order['address'],$order['condition']));
        }
        return $orders;
    }

    /**
     * 根据用户查找订单
     * @param $client string 用户id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectOrdersByClient($client){
        $listOrders = $this->table('orders')->where('client',$client)->field('id,client,hotel,money,address,condition')->select();
        $orders=[];
        foreach ($listOrders as $order){
            $menu = $this->table('order_menu')->where('order_id',$order['id'])->field('name,price,count')->select();
            array_push($orders,new Order($order['id'],$order['client'],$order['hotel'],$menu,$order['money'],$order['address'],$order['condition']));
        }
        return $orders;
    }

    /**
     * 全部订单 不包括菜单
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectAllOrders(){
        $orders=[];
        $listOrders = $this->table('orders')->field('id,client,hotel,money,address,condition')->select();
        foreach ($listOrders as $order){
            array_push($orders,new Order($order['id'],$order['client'],$order['hotel'],null,$order['money'],$order['address'],$order['condition']));
        }
        return $orders;
    }

    /**
     * 查询指定店家的订单数
     * @param $hotel
     * @return int|string
     * @throws \think\Exception
     */
    public function countByHotel($hotel){
        return $this->table('orders')->where('hotel',$hotel)->count();
    }
}