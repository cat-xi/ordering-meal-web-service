window.onload=function(){
    //商品展示界面
    Vue.component('hotel-show', {
        props: {
            dish: Object
        },
        data: function () {
            return {
                select: false
            }
        },
        template:
            `<div style="background-color: white;position: relative">
                <el-row>
                    <el-col :span="8">
                        <img v-bind:src="dish.picture" style="width: 100px;height: 100px" alt="">
                    </el-col>
                    <el-col :span="10">
                        <h4>{{dish.name}}</h4>
                        <span style="color: red">{{dish.price}}¥</span>
                    </el-col>
                    <el-col :span="6">
                        <el-input-number v-if="select" v-model="dish.count" size="mini" @change="handleChange" :min="0" label="描述文字" style="position: absolute;right: 0;bottom: 0;"></el-input-number>
                        <el-button v-else type="success" round size="small" v-on:click="select=true;dish.count=1" style="position: absolute;right: 0;bottom: 0">加入购物车</el-button>
                    </el-col>
                </el-row>
            </div>`
        ,methods:{
            //购物车商品数量更改
            handleChange(){
                if (this.dish.count===0){
                    this.select=false;
                }
            },
        }
    })
    var app = new Vue({
        el:"#app",
        data:{
            //店家信息
            hotel:{
                name:'',
                portrait:'',
            },
            //菜单
            menu:[]
        },
        computed: {
            //价格
            money:function(){
                if (this.menu===null)
                    return 0;
                return this.menu.reduce((a,x)=>a+x.count*x.price,0)
            },
            //菜品数量
            counts:function () {
                if (this.menu===null)
                    return 0;
                return this.menu.reduce((a,x)=>a+x.count,0)
            }
        },
        methods:{
            //结算方法
            settlement:function () {
                console.log(JSON.stringify(this.menu));
                console.log(this.money);
                Vue.http.post("/index.php/index/Client/makeOrder",{menu:JSON.stringify(this.menu),money:this.money,tel:tel}).then(res => {
                    console.log(res.body);
                    window.location.href="order.html";
                    //跳转结算页面
                },response => {
                    console.log("error");
                })
            }
        }
    });
    //获取url后?tel
    var r = window.location.search.substr(1).match(new RegExp("(^|&)" + 'tel' + "=([^&]*)(&|$)"));
    var tel;
    if (r != null){
        tel = unescape(r[2]);
        console.log(tel);
    }
    else {
        window.location.href="hotel.html";
    }
    //post 获取店家信息及菜单
    Vue.http.post("/index.php/index/Client/findHotelByTel",{tel:tel}).then(res => {
        console.log(res.body);
        app.hotel = res.body.data.hotel;
        app.menu = res.body.data.menu;
    },response => {
        console.log("error");
        window.location.href="index.html";
    })
}