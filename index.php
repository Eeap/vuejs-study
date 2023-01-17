<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@3.1.1/dist/vuetify.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div id="app">
        <v-app>
            <v-app-bar app color="cyan" dark>
                <v-app-bar-title>
                    <div id="head"align="center">{{message}}</div>
                    <a href="/calendar"><v-btn tile color="error">캘린더</v-btn></a>
                </v-app-bar-title>
            </v-app-bar>
            <v-main id="app">
                <v-container>
                    <v-row>
                        <v-col cols="12" md="2">
                            <a href="/write" method="get">
                                <v-btn color="cyan" :style="{height:'50px', width:'80px', fontWeight:'bold', fontSize:'large'}">글작성</v-btn>        
                            </a>
                        </v-col>
                    </v-row>
            
                    <v-card>
                        <v-card-title>게시판</v-card-title>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>번호</th>
                                        <th>내용</th>
                                        <th>작성자</th>
                                        <th>업데이트</th>
                                        <th>등록시간</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in list" :key="item.no">
                                        <td>{{ item.no }}</td>
                                        <td>{{ item.content }}</td>
                                        <td>{{ item.author }}</td>
                                        <td>{{ item.update }}</td>
                                        <td>{{ item.created }}</td>
                                        <td><v-btn @click="editBoard(item.no)" tile color="success"><v-icon left>mdi-pencil</v-icon>
                                        Edit
                                        </v-btn></td>
                                        <td><a href="/"><v-btn tile color="error" @click="delBoard(item.no)">X</v-btn></a></td>
                                    </tr>
                                </tbody>
                            </table>
                    </v-card>
                </v-container>
            </v-main>
        </v-app>
    </div>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@3.1.1/dist/vuetify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/vue-router@3.0.0/dist/vue-router.js"></script>
    <script>
        const { createApp } = Vue
        const { createVuetify } = Vuetify
        const vuetify = createVuetify()
        const app = createApp({
            data() {
                return {
                    message: "메인 게시판",
                    list:[],
                    url:""
                }
            },
            methods: {
                delBoard:function(index) {
                    console.log("test");
                    console.log(index);
                    axios.post("/board/delete",index)
                    .then(res=>{console.log(res);})
                },
                editBoard:function(index) {
                    this.url="/board/edit?id="+index;
                    location.href=this.url;
                }
            },
            created() {
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                axios.get("/board")
                    .then(res => {
                        for (let item in res['data']){
                            console.log(item);
                            this.list.push({
                                no:res['data'][item]['id'],
                                content:res['data'][item]['content'],
                                author:res['data'][item]['author'],
                                update:res['data'][item]['updatetime'],
                                created:res['data'][item]['createtime']});
                        }
                        console.log(res['data']);
                    });
                    console.log("test");
            }
        })
        app.use(vuetify).mount('#app')
    </script>
</body>
</html>