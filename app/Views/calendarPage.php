<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.6.14/dist/vuetify.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
    <div id="app">
        <v-app>
            <v-app-bar app color="cyan" dark absolute>
                <v-toolbar-title>Calendar</v-toolbar-title>
            </v-app-bar>
            <v-main id="app">
                <v-container>
                    <v-row>
                        <v-col order="2"></v-col>
                        <v-card>
                            <v-card-title></v-card-title>
                                <v-date-picker v-model="picker"></v-date-picker>
                            <v-divider></v-divider>
                            <v-spacer></v-spacer>
                            <v-form ref="form" @submit.prevent="save" v-model="valid" lazy-validation>
                                <v-text-field id="content" name="content" type="text" v-model="content" variant="filled" label="내용" required></v-text-field>
                                <v-text-field id="money" name="money" type="text" v-model="money" label="금액" required></v-text-field>
                                    <a href="/calendar">
                                    <v-btn color="success" class="mr-4" @click="register">
                                        등록
                                    </v-btn>                   
                                    </a>
                            </v-form>
                        </v-card>
                    </v-row>
                </v-container>
            </v-main>
        </v-app>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.6.14/dist/vuetify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                events: [{
                    name:"dummy",
                    start:new Date(1999,0,1),
                    color:'red'
                }],
                colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
                picker: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                content:"",
                money:"",
            },
            mounted () {
            },
            methods: {
                register:function(){
                    //db데이터 넣기
                    var temp =new Date(this.picker);
                    let year = temp.getFullYear();
                    let month = temp.getMonth();
                    let day = temp.getDate();
                    
                    console.log(temp);
                    axios({
                        url:"/calendar/write",
                        method:'post',
                        data:{
                            content:this.content,
                            money:this.money,
                            year:year,
                            month:month,
                            day:day
                        },
                        header:{ 'Content-type': 'application/json'}
                    })
                    .then(res => {
                        console.log(res);
                    }).catch(err =>{console.log(err);});
                },
            },
            created() {
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                
            }
        })
    </script>
</body>
</html>