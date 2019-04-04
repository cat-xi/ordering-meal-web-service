window.onload=function () {
    Vue.component('home-page', {
        props: {
            home: Object
        },
        template:
            `<div style="height: 100%;">
                <el-row>
                    <el-col :span="2">
                        <h1>个人信息</h1>
                    </el-col>
                </el-row>
                <div style="background-color: white;padding: 20px;height: 180px">
                    <el-row style="height: 100%">
                        <el-col :span="6" :offset="3" style="height: 100%">
                            <img v-bind:src="home.portrait" style="height: 100%">
                        </el-col>
                        <el-col :span="12">
                            <el-row>
                                <el-col :span="12" style="height: 60px;padding: 5px">
                                    <el-input v-bind:placeholder="home.name" :disabled="true">
                                        <template slot="prepend">名&nbsp;&nbsp;&nbsp;&nbsp;称</template>
                                    </el-input>
                                </el-col>
                                <el-col :span="12" style="height: 60px;padding: 5px">
                                    <el-input v-bind:placeholder="home.tel" :disabled="true">
                                        <template slot="prepend">电&nbsp;话</template>
                                    </el-input>
                                </el-col>
                                <el-col :span="12" style="height: 60px;padding: 5px">
                                    <el-input v-bind:placeholder="home.location" :disabled="true">
                                        <template slot="prepend">位&nbsp;&nbsp;&nbsp;&nbsp;置</template>
                                    </el-input>
                                </el-col>
                                <el-col :span="12" style="height: 60px;padding: 5px">
                                    <el-input v-bind:placeholder="home.cuisine" :disabled="true">
                                        <template slot="prepend">菜&nbsp;系</template>
                                    </el-input>
                                </el-col>
                                <el-col :span="12" style="height: 60px;padding: 5px">
                                    <el-input v-bind:placeholder="home.orderCount" :disabled="true">
                                        <template slot="prepend">&nbsp;订单量</template>
                                    </el-input>
                                </el-col>
                                <el-col :span="12" style="height: 60px;padding: 5px">
                                    <el-input v-bind:placeholder="home.state" :disabled="true">
                                        <template slot="prepend">状&nbsp;态</template>
                                    </el-input>
                                </el-col>
                            </el-row>
                        </el-col>
                    </el-row>
                </div>
                 <el-row>
                    <el-col :span="2">
                        <h1>审核状态</h1>
                    </el-col>
                </el-row>
                <div style="background-color: white;padding: 20px;">
                    <el-row>
                        <el-steps :space="500" :active="1+home.examine+home.online" align-center finish-status="success">
                            <el-step title="注册"></el-step>
                            <el-step title="审核"></el-step>
                            <el-step title="录入菜单"></el-step>
                            <el-step title="上线"></el-step>
                        </el-steps>
                    </el-row>
                </div>
            </div>`
    })
    Vue.component('menu-page', {
        props: {
            hotel: Object
        },
        template:
            `<div style="height: 100%;background-color: mediumpurple">

            </div>`
    })
    Vue.component('order-page', {
        props: {
            hotel: Object
        },
        template:
            `<div style="height: 100%;background-color: mediumpurple">
                订单
            </div>`
    })
    Vue.component('set-page', {
        props: {
            hotel: Object
        },
        template:
            `<div style="height: 100%;background-color: mediumpurple">
                设置
            </div>`
    })
    var app = new Vue({
        el: '#app',
        data: {
            //视图
            view:1,
            //首页面数据
            home:{

            }
        },
        methods:{
            showPage(index, indexPath) {
                switch (index) {
                    case '1':
                        Vue.http.get("/index.php/index/Store/oneself").then(res => {
                            app.home=res.body.data
                            console.log(res.body.data );
                            console.log("页面刷新了")
                        },response => {
                            console.log("asd");
                            // self.location='http://localhost:8888/frontEnd/el-hotel/login.html';
                        })
                        break;
                    case '2':
                        break;
                }
                this.view = index;
            }
        }
    });
    /**
     * 抓取首页面数据
     */
    Vue.http.get("/index.php/index/Store/oneself").then(res => {
        app.home=res.body.data
        console.log(res.body.data );
        console.log("页面刷新了")
    },response => {
        console.log("asd");
        // self.location='http://localhost:8888/frontEnd/el-hotel/login.html';
    })
}