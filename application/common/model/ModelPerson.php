<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-03
 * Time: 11:13
 */

namespace app\common\model;


use app\common\pojo\Person;
use think\Model;

class ModelPerson extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'person';

    /**
     * 添加用户
     * @param Person $person
     */
    public function insertPerson(Person $person){

    }

    /**
     * 根据tel 查找用户
     * @param $tel
     * @return Person
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectPersonByTel($tel){
        $per = $this->where("tel",$tel)->field('tel,password,admin')->find();
        return new Person($per['tel'],$per['password'],$per['admin']);
    }
}