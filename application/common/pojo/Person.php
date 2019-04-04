<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-03
 * Time: 11:14
 */

namespace app\common\pojo;


class Person
{
    public $tel;//电话
    public $password;//密码
    public $admin;//管理员

    /**
     * Person constructor.
     * @param $tel
     * @param $password
     * @param $admin
     */
    public function __construct($tel, $password, $admin)
    {
        $this->tel = $tel;
        $this->password = $password;
        $this->admin = $admin;
    }

}