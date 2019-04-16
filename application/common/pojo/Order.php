<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-15
 * Time: 13:11
 */

namespace app\common\pojo;

/**
 * Class Order
 * @package app\common\pojo 订单对象
 */
class Order
{
    public $id;//id
    public $client;//用户
    public $hotel;//店家
    public $menu;//菜单 数组(id,name,price,count)
    public $money;//价格
    public $address;//收货地址
    public $condition;

    /**
     * Order constructor.
     * @param $id
     * @param $client
     * @param $hotel
     * @param $menu
     * @param $money
     * @param $address
     * @param $condition
     */
    public function __construct($id, $client, $hotel, $menu, $money, $address, $condition)
    {
        $this->id = $id;
        $this->client = $client;
        $this->hotel = $hotel;
        $this->menu = $menu;
        $this->money = $money;
        $this->address = $address;
        $this->condition = $condition;
    }//状态 -1为创建订单但没有进入数据库(没有付费) 0为订单进入数据库(已经付费) 1为店家接单 2为配送完毕完成订单



}