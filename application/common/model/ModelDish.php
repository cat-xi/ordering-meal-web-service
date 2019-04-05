<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-05
 * Time: 14:27
 */

namespace app\common\model;


use app\common\pojo\Dish;
use think\Model;

class ModelDish extends Model
{
    /**
    id int auto_increment,
    name varchar(20) not null,
    tel char(11) not null,
    price double not null,
    picture mediumblob not null,
     */
    // 设置当前模型对应的完整数据表名称
    protected $table = 'dishes';

    /**
     * 根据店家tel 查找菜单
     * @param $tel
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectDishesByTel($tel){
        $dishes=[];
        $list = $this->where('tel',$tel)->field('id,name,tel,price,picture')->select();
        foreach ($list as $dish){
            array_push($dishes,new Dish($dish['id'],$dish['tel'],$dish['name'],$dish['price'],$dish['picture']));
        }
        return $dishes;
    }

    /**
     * 批量添加菜品
     * @param $dishes
     * @return array|false
     * @throws \Exception
     */
    public function insertDishes($dishes){
        $data=[];
        foreach ($dishes as $dish){
            array_push($data,array(
                'name'=>$dish->name,
                'tel'=>$dish->tel,
                'price'=>$dish->price,
                'picture'=>$dish->picture
            ));
        }
        return $this->saveAll($data);
    }

    /**
     * 根据id 查找菜品
     * @param $id
     * @return Dish
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectDishById($id){
        $dish = $this->where("id",$id)->field('id,name,tel,price,picture')->find();
        return new Dish($dish['id'],$dish['tel'],$dish['name'],$dish['price'],$dish['picture']);
    }
//    public function selectAllHotels(){
//        $hotels=[];
//        $list = $this->field('name,tel,password,location,cuisine,examine,online,portrait')->select();
//        foreach ($list as $hotel){
//            array_push($hotels,new Hotel($hotel['name'],$hotel['tel'],$hotel['password'],$hotel['location'],$hotel['cuisine'],$hotel['examine'],$hotel['online'],$hotel['portrait']));
//        }
//        return $hotels;
//    }
}