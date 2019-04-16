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
}