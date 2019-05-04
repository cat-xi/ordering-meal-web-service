## 订餐系统项目介绍
### 架构
+ 后端 apache2+thinkphp+mysql
+ 前端 vue+element
+ 数据库表结构
```sql
create table hotel_user
(
	name varchar(20) not null,
	tel char(11) not null,
	password char(20) not null,
	location varchar(50) not null,
	cuisine varchar(20),
	examine boolean default false null,
	menu boolean default false null,
	online boolean default false null,
	portrait mediumblob null,
	constraint hotel_user_pk
		primary key (tel)
)
comment '店家用户表';

create table person
(
	tel char(11) not null,
	password varchar(20) not null,
	admin boolean default false null,
	constraint person_pk
		primary key (tel)
);

create table dishes
(
	id int auto_increment,
	name varchar(20) not null,
	tel char(11) not null,
	price double not null,
	picture mediumblob not null,
	constraint dishes_pk
		primary key (id)
)
comment '菜品表';

create table orders
(
	id int auto_increment,
	client char(11) not null,
	hotel char(11) not null,
	money int null,
	address varchar(200) not null,
	`condition` int not null,
	constraint order_pk
		primary key (id)
)
comment '订单表';

create table order_menu
(
	id int auto_increment,
	order_id int not null,
	name varchar(20) not null,
	price double not null,
	count int not null,
	constraint order_menu_pk
		primary key (id)
)
comment '订单菜单表';
```
### 模块介绍
##### 管理员模块
+ 登陆
+ 仪表盘(显示基础信息)
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/5.png)
+ 审核
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/2.png)
+ 上线
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/3.png)
+ 显示全部店家信息
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/4.png)
##### 店家模块
+ 登陆
+ 注册
+ 基础信息
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/6.png)
+ 菜单处理
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/1.png)
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/7.png)
+ 订单处理
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/8.png)
##### 用户模块
+ 登陆
+ 注册
+ 选择店家
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/11.png)
+ 订餐
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/12.png)
+ 订单处理
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/13.png)
![](https://github.com/quan930/ordering-meal-web-service/blob/master/showimage/9.png)
### URL
+ webRoot`public`
+ 管理员`http://localhost/frontEnd/el-admin`
+ 店家`http://localhost/frontEnd/el-hotel`
+ 客户端 `http://localhost:8888/frontEnd/el-client`