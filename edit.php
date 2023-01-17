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
                </v-app-bar-title>
            </v-app-bar>
            <v-main id="app">
                <v-container>
                    <v-card>
                        <v-card-title></v-card-title>
                        <v-form ref="form" @submit.prevent="save" v-model="valid" lazy-validation>
                                <v-text-field id="author" name="author" type="text" v-model="author" label="작성자" required></v-text-field>
                                <v-text-field id="content" name="content" type="text" v-model="content" variant="filled" label="내용" required></v-text-field>
                                    <a href="/">
                                        <v-btn tile color="success" @click="udpate">수정 완료
                                        </v-btn>           
                                    </a>
                            </v-form>
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
                    message: "test",
                    list:[],
                    author:"",
                    content:""
                }
            },
            methods: {
                udpate:function(){
                    //db데이터 넣기
                
                    axios({
                        url:"/board/put?id="+<?php echo $id;?>,
                        method:'post',
                        data:{
                            author:this.author,
                            content:this.content
                        },
                        header:{ 'Content-type': 'application/json'}
                    })
                    .then(res => {
                        console.log(res);
                    }).catch(err =>{console.log(err);});
                }
            },
            created() {
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                axios.get("/board/detail?id="+<?php echo $id;?>)
                    .then(res => {
                        for (let item in res['data']){
                            console.log(item);
                            this.list.push({
                                no:res['data'][item]['id'],
                                content:res['data'][item]['content'],
                                author:res['data'][item]['author'],
                                update:res['data'][item]['updatetime'],
                                created:res['data'][item]['createtime']});
                            this.author = res['data'][item]['author'];
                            this.content = res['data'][item]['content'];
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