<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-03-30
 * Time: 15:18
 */

namespace app\common\pojo;


class Hotel
{
    public $name;//名称
    public $tel;//电话
    public $password;//密码
    public $location;//位置
    public $cuisine;//菜系
    public $examine;//审核
    public $menu;//菜单 false为没有菜单 true为菜单已经上传
    public $online;//上线
    public $portrait;

    /**
     * Hotel constructor.
     * @param $name
     * @param $tel
     * @param $password
     * @param $location
     * @param $cuisine
     * @param $examine
     * @param $menu
     * @param $online
     * @param $portrait
     */
    public function __construct($name, $tel, $password, $location, $cuisine, $examine, $menu, $online, $portrait)
    {
        $this->name = $name;
        $this->tel = $tel;
        $this->password = $password;
        $this->location = $location;
        $this->cuisine = $cuisine;
        $this->examine = $examine;
        $this->menu = $menu;
        $this->online = $online;
        $this->portrait = $portrait;
    }//头像



}