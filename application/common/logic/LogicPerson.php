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
}