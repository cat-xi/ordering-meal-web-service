<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-03
 * Time: 11:41
 */

namespace app\common\logic;


use app\common\pojo\Person;
use think\Model;

class LogicPerson extends Model
{
    /**
     * 根据账号 密码判断管理员
     * @param $tel
     * @param $password
     * @return int 返回1:成功 返回0:密码错误 返回-1:内容为null
     */
    public function isAdminByTel($tel,$password){
        if ($tel==null||$password==null)
            return -1;
        $person = \model('ModelPerson','model')->selectPersonByTel($tel);
        if ($person->password!=$password){
            //密码错误
            return 0;
        }
        return 1;
    }

    /**
     * 添加用户
     * @param Person $person 用户对象
     * @return int 返回1添加成功 返回-1内容为null 返回0添加失败
     */
    public function addClient(Person $person){
        if ($person->password==null||$person->tel==null)
            return -1;
        $person->admin=false;
        return \model('ModelPerson','model')->insertPerson($person);
    }

    /**
     * 登陆
     * @param Person $person
     * @return int 成功返回1 密码错误返回0 内容为null返回-1
     */
    public function login(Person $person){
        if ($person->tel==null||$person->password==null)
            return -1;
        $per = \model('ModelPerson','model')->selectPersonByTel($person->tel);
        if ($per->password==$person->password)
            return 1;
        return 0;
    }
}