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
    //菜单页面
    Vue.component('menu-page', {
        props: {
            menu: Object
        },
        template:
            `<div style="height: 100%;">
                <el-row>
                    <el-col :span="2">
                        <h1>菜单</h1>
                    </el-col>
                </el-row>
                <el-table :data="menu.table"
                        height="300"
                        border
                        style="width: 100%">
                    <el-table-column label="图片" width="800">
                        <template slot-scope="scope">
                            <img v-bind:src="menu.table[scope.$index].picture" style="height: 100px">
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
                <el-row>
                    <el-col :span="2">
                        <h1>上传菜单</h1>
                    </el-col>
                </el-row>
                <div style="background: white;height: 200px">
                    <el-row v-if="menu.fun==0" :gutter="10">
                        <el-col :span="6">
                            <el-button style="width:100%" v-on:click="fun" type="primary">添加菜品</el-button>
                        </el-col>
                    </el-row>
                    <el-form v-if="menu.fun==1" :model="menu.uploadMenu" ref="menu.uploadMenu" label-width="100px" class="demo-dynamic">
                    <el-form-item
                            v-for="(dish, index) in menu.uploadMenu.menus"
                            :label="'菜品' + (index+1)"
                            :rules="{
                  required: true, message: '域名不能为空', trigger: 'blur'
                }"
                    >
                        <el-row :gutter="10">
                            <el-col :span="4">
                                <el-input v-model="dish.name">
                                    <template slot="prepend">菜名</template>
                                </el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-input v-model="dish.price">
                                    <template slot="prepend">价格</template>
                                </el-input>
                            </el-col>
                            <el-col :span="8">
                                <span>菜品图片:</span>
                                <input :id="index" type="file" accept="image/*">
                            </el-col>
                            <el-col :span="4">
                                <el-button @click.prevent="removeDomain(dish)" style="width:100%">删除</el-button>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('menu.uploadMenu')">提交</el-button>
                        <el-button @click="addMenu">新增菜品</el-button>
                    </el-form-item>
                </el-form>
                </div>
            </div>`,
        methods:{
            //提交菜品
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        let formData = new FormData();
                        var menu=[];
                        console.log("待上传菜单")
                        for(i = 0; i < this.menu.uploadMenu.menus.length; i++) {
                            formData.append(i,document.getElementById(i).files[0]);
                            menu.push({name:this.menu.uploadMenu.menus[i].name,price:this.menu.uploadMenu.menus[i].price})
                        }
                        formData.append("menu",JSON.stringify(menu));
                        Vue.http.post("/index.php/index/Store/uploadMenu",formData).then(res => {
                            console.log("通过")
                            this.menu.table=res.body.data;
                        },response => {
                            alert('失败');
                            // self.location='http://localhost:8888/frontEnd/el-admin/login.html';
                        })
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
                this.menu.fun="0";
            },
            removeDomain(item) {
                var index = this.menu.uploadMenu.menus.indexOf(item)
                if (index !== -1) {
                    this.menu.uploadMenu.menus.splice(index, 1)
                }
            },
            addMenu() {
                this.menu.uploadMenu.menus.push({
                    name: '',
                    price:'',
                });
            },
            fun(){
                this.menu.fun="1";
                this.menu.uploadMenu.menus=[{
                    name: '',
                    price:'',
                }]
            }
        }
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

            },
            //菜单
            menu:{
                //上传菜单
                uploadMenu: {
                    menus: [{
                        name: '',
                        price:'',
                    }]
                },
                //表格数据
                table:[],
                fun:'0'
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
                        Vue.http.get("/index.php/index/Store/menu").then(res => {
                            app.menu.table=res.body.data
                            console.log(res.body.data );
                            console.log("页面刷新了")
                        },response => {
                            console.log("asd");
                            // self.location='http://localhost:8888/frontEnd/el-hotel/login.html';
                        })
                        break;
                }
                this.view = index;
            },
            offline(){
                Vue.http.get("/index.php/index/Store/offline").then(res => {
                    self.location='http://localhost:8888/frontEnd/el-hotel/login.html';
                },response => {
                    alert("下线失败");
                    console.log("asd");
                    // self.location='http://localhost:8888/frontEnd/el-hotel/login.html';
                })
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
        self.location='http://localhost:8888/frontEnd/el-hotel/login.html';
    })
}