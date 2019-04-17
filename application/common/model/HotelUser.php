<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-03-30
 * Time: 15:34
 */

namespace app\common\model;


use app\common\pojo\Hotel;
use think\Model;

class HotelUser extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'hotel_user';

    /**
     * 添加店家
     * @param Hotel $hotel
     * @return false|int
     */
    public function insertHotel(Hotel $hotel){
        $this->data([
            'name'=>$hotel->name,
            'tel'=>$hotel->tel,
            'password'=>$hotel->password,
            'location'=>$hotel->location,
            'cuisine'=>$hotel->cuisine,
            'examine'=>$hotel->examine,
            'menu'=>$hotel->menu,
            'online'=>$hotel->online,
            'portrait'=>$hotel->portrait
        ]);
        return $this->save();
    }

    /**
     * 更新店家
     * @param Hotel $hotel
     * @return false|int 返回-1没有tel 返回0更新失败 返回1更新成功
     */
    public function updateHotel(Hotel $hotel){
        $arr = [];
        if ($hotel->tel==null)
            return -1;
        if ($hotel->password!=null)
            $arr = $arr+array('password'=>$hotel->password);
        if ($hotel->name!=null)
            $arr = $arr+array('name'=>$hotel->name);
        if ($hotel->location!=null)
            $arr = $arr+array('location'=>$hotel->location);
        if ($hotel->cuisine!=null)
            $arr = $arr+array('cuisine'=>$hotel->cuisine);
        if ($hotel->examine!==null)
            $arr = $arr+array('examine'=>$hotel->examine);
        if ($hotel->menu!==null)
            $arr = $arr+array('menu'=>$hotel->menu);
        if ($hotel->online!==null)
            $arr = $arr+array('online'=>$hotel->online);
        if ($hotel->portrait!=null)
            $arr = $arr+array('portrait'=>$hotel->portrait);
        return $this->save($arr,array('tel'=>$hotel->tel));
    }

    /**
     * 全部店家
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectAllHotels(){
        $hotels=[];
        $list = $this->field('name,tel,password,location,cuisine,examine,menu,online,portrait')->select();
        foreach ($list as $hotel){
            array_push($hotels,new Hotel($hotel['name'],$hotel['tel'],$hotel['password'],$hotel['location'],$hotel['cuisine'],$hotel['examine'],$hotel['menu'],$hotel['online'],$hotel['portrait']));
        }
        return $hotels;
    }

    /**
     * 根据tel查找店家
     * @param $tel
     * @return Hotel
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectHotelByTel($tel){
        $hotel = $this->where("tel",$tel)->field('name,tel,password,location,cuisine,examine,menu,online,portrait')->find();
        return new Hotel($hotel['name'],$hotel['tel'],$hotel['password'],$hotel['location'],$hotel['cuisine'],$hotel['examine'],$hotel['menu'],$hotel['online'],$hotel['portrait']);
    }
}