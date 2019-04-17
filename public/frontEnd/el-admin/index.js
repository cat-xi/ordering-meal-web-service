/**
 * 判断登陆 否则进入登陆界面
 * 执行首页数据方法
 */
window.onload=function () {
    Vue.component('home-page', {
        props: {
            home: Object
        },
        template:
            `<div style="height: 100%;">
                <el-row>
                    <el-col :span="2">
                        <h1>仪表盘</h1>
                    </el-col>
                </el-row>
                <div style="background-color: white;padding: 20px">
                    <el-row :gutter="0">
                        <el-col :span="4" :offset="5">
                            <el-progress type="circle" :percentage="100" status="text">注册店家:{{home.allHotel}}</el-progress>
                        </el-col>
                        <el-col :span="4" :offset="1">
                            <el-progress type="circle" :percentage="home.examineHotel/home.allHotel*100" status="text">已审核</el-progress>
                        </el-col>
                        <el-col :span="4" :offset="1">
                            <el-progress type="circle" :percentage="home.onlineHotel/home.allHotel*100" color="#8e71c7" status="text">已上线</el-progress>
                        </el-col>
                    </el-row>
                    <el-row :gutter="30">
                        <el-col :span="4" :offset="4">
                            <el-progress type="circle" :percentage="100" status="text">注册人数:{{home.user}}</el-progress>
                        </el-col>
                        <el-col :span="4">
                            <el-progress type="circle" :percentage="100" status="text">订单数:{{home.order}}</el-progress>
                        </el-col>
                        <el-col :span="4">
                            <el-progress type="circle" :percentage="home.successOrder/home.order*100" color="#67C23A" status="text">已完成订单:{{home.successOrder}}</el-progress>
                        </el-col>
                        <el-col :span="4">
                            <el-progress type="circle" :percentage="home.failureOrder/home.order*100" color="#FF6C6C" status="text">未完成订单:{{home.failureOrder}}</el-progress>
                        </el-col>
                    </el-row>
                </div>
            </div>`
    })
    Vue.component('review-page', {
        props: {
            review: Array
        },
        template:
            `<div>
                <el-row>
                    <el-col :span="2">
                        <h1>待审核</h1>
                    </el-col>
                </el-row>
                <el-table :data="review"
                    height="250"
                    border
                    style="width: 100%">
                    <el-table-column
                            prop="name"
                            label="店家名称">
                    </el-table-column>
                    <el-table-column
                            prop="tel"
                            label="电话">
                    </el-table-column>
                    <el-table-column
                            prop="location"
                            label="位置">
                    </el-table-column>
                    <el-table-column
                            prop="cuisine"
                            label="菜系">
                    </el-table-column>
                    <el-table-column label="审核">
                        <template slot-scope="scope">
                            <el-button size="mini" type="danger" v-on:click="audit(scope.$index)">审核
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>`
        ,methods: {
            audit(index) {
                var tel = this.review[index].tel;
                console.log(tel);
                // /index.php/index/Admin/audit
                Vue.http.post("/index.php/index/Admin/audit",{tel:tel}).then(res => {
                    console.log(res.body.data );
                    console.log("审核通过")
                    app.review=res.body.data;
                },response => {
                    alert('审核失败');
                    // self.location='http://localhost:8888/frontEnd/el-admin/login.html';
                })
            }
        }
    })
    //未上线页面
    Vue.component('online-page', {
        props: {
            online: Array
        },
        template:
            `<div>
                <el-row>
                    <el-col :span="2">
                        <h1>待上线</h1>
                    </el-col>
                </el-row>
                <el-table :data="online"
                      height="500"
                      border
                      style="width: 100%">
                <el-table-column
                        prop="name"
                        label="店家名称">
                </el-table-column>
                <el-table-column
                        prop="tel"
                        label="电话">
                </el-table-column>
                <el-table-column
                        prop="location"
                        label="位置">
                </el-table-column>
                <el-table-column
                        prop="cuisine"
                        label="菜系">
                </el-table-column>
                <el-table-column
                        width="500"
                        label="菜品">
                    <el-table slot-scope="scope" :data="online[scope.$index].menu" width="400" height="200">
                        <el-table-column
                                label="图片">
                                <h1>{{online[scope.$index].menu}}</h1>
                                <template slot-scope="sco">
                                    <img style="height: 100px" v-bind:src="online[scope.$index].menu[sco.$index].picture">
                                </template>
                        </el-table-column>
                        <el-table-column
                                prop="name"
                                label="菜名">
                        </el-table-column>
                        <el-table-column
                                prop="price"
                                label="价格">
                        </el-table-column>
                    </el-table>
                </el-table-column>
                <el-table-column label="审核">
                    <template slot-scope="scope">
                        <el-button size="mini" type="danger" v-on:click="onlineHotel(scope.$index)">上线</el-button>
                    </template>
                </el-table-column>
            </el-table>
            </div>`,
        methods: {
            onlineHotel(index) {
                var tel = this.online[index].tel;
                console.log(tel);
                // /index.php/index/Admin/audit
                Vue.http.post("/index.php/index/Admin/online",{tel:tel}).then(res => {
                    console.log(res.body.data );
                    console.log("审核通过")
                    this.online=res.body.data;
                },response => {
                    alert('审核失败');
                    // self.location='http://localhost:8888/frontEnd/el-admin/login.html';
                })
            }
        }
    })
    Vue.component('store-page', {
        props: {
            hotel: Object
        },
        template:
            `<div style="height: 100%;background-color: mediumpurple">
                店家界面
            </div>`
    })
    var app = new Vue({
        el: '#app',
        data: {
            view:'1',
            /**
             * c初始化首页面所需数据
             */
            data:{
                allHotel:100,
                examineHotel:100,
                onlineHotel:100,
                order:100,
                user:0,
                successOrder:100,
                failureOrder:100,
            },
            review:[],
            online:[]
        },
        methods:{
            /**
             * 切换视图
             * @param index
             * @param indexPath
             */
            showPage(index, indexPath) {
                switch (index) {
                    case '1':
                        console.log("首页面");
                        Vue.http.get("/index.php/index/Admin/homePage").then(res => {
                            console.log(res.body.data );
                            console.log("首页面数据刷新")
                            this.data=res.body.data
                        },response => {
                            self.location='http://localhost:8888/frontEnd/el-admin/login.html';
                        })
                        break;
                    case '2':
                        console.log("待审核页面");
                        Vue.http.get("/index.php/index/Admin/reviewPage").then(res => {
                            console.log(res.body.data );
                            console.log("审核页面数据")
                            this.review=res.body.data;
                        },response => {
                            // self.location='http://localhost:8888/frontEnd/el-admin/login.html';
                        })
                        break;
                    case '3':
                        console.log("待上线页面");
                        Vue.http.get("/index.php/index/Admin/onlinePage").then(res => {
                            console.log(res.body.data );
                            console.log("上线页面数据")
                            this.online=res.body.data;
                        },response => {
                            // self.location='http://localhost:8888/frontEnd/el-admin/login.html';
                        })
                        break;
                }
                this.view = index;
            }
        },
    });
    /**
     * 抓取首页面数据
     */
    Vue.http.get("/index.php/index/Admin/homePage").then(res => {
        console.log(res.body.data );
        console.log("页面刷新了")
        app.data=res.body.data
    },response => {
        console.log("asd");
        self.location='http://localhost:8888/frontEnd/el-admin/login.html';
    })
}