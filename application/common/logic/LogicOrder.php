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
}