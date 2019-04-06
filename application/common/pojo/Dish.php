<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-05
 * Time: 14:24
 */

namespace app\common\pojo;

/**
 * Class Dish 菜品
 * @package app\common\pojo
 */
class Dish
{
    public $id;//id
    public $tel;//店家id
    public $name;//菜名
    public $price;//价格
    public $picture;//图片

    /**
     * Dish constructor.
     * @param $id
     * @param $tel
     * @param $name
     * @param $price
     * @param $picture
     */
    public function __construct($id, $tel, $name, $price, $picture)
    {
        $this->id = $id;
        $this->tel = $tel;
        $this->name = $name;
        $this->price = $price;
        $this->picture = $picture;
    }//菜品图品

}