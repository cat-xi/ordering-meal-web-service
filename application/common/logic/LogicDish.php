<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-05
 * Time: 16:14
 */

namespace app\common\logic;


use think\Model;

class LogicDish extends Model
{
    private $showUrl = '/index.php/dish/';
    /**
     * @param $dishes 批量添加菜单数据
     * @return int
     */
    public function addDishes($dishes){
        return count(\model('ModelDish','model')->insertDishes($dishes));
    }

    /**
     * 根据店家 查找菜品
     * @param $tel
     * @return mixed
     */
    public function findDishesByTel($tel){
        $dishes = \model('ModelDish','model')->selectDishesByTel($tel);
        foreach ($dishes as $dish){
            $dish->picture=$this->showUrl.$dish->id;
        }
        return $dishes;
    }
    /**
     * 返回菜单二进制数据
     * @param $id string 菜单id
     * @return mixed
     */
    public function findImageById($id){
        return \model('ModelDish','model')->selectDishById($id)->picture;
    }
}