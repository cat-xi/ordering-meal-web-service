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
+ 审核
+ 上线
+ 显示全部店家信息
##### 店家模块
+ 登陆
+ 注册
+ 基础信息
+ 菜单处理
+ 订单处理
##### 用户模块
+ 登陆
+ 注册
+ 选择店家
+ 订餐
+ 订单处理