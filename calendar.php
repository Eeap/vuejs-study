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
                        <v-col cols="7">
                        <v-sheet height="64">
                            <v-toolbar flat>
                                <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">Today</v-btn>
                                <v-btn fab text small color="grey darken-2" @click="prev"><v-icon small>mdi-chevron-left</v-icon></v-btn>
                                <v-btn fab text small color="grey darken-2" @click="next"><v-icon small>mdi-chevron-right</v-icon></v-btn>
                                <v-toolbar-title v-if="$refs.calendar">{{ $refs.calendar.title }}</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-title>총 지출 금액: {{ sum }}</v-toolbar-title>
                            </v-toolbar>
                        </v-sheet>
                        <v-sheet height="600">
                            <v-calendar
                            ref="calendar"
                            v-model="focus"
                            color="primary"
                            :events="events"
                            :event-color="getEventColor"
                            :type="type"
                            @click:event="showEvent"
                            @click:more="viewDay"
                            @click:date="viewDay"
                            ></v-calendar>
                            <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
                                <v-card color="grey lighten-4" min-width="350px" flat>
                                    <v-toolbar :color="selectedEvent.color" dark>
                                        <v-btn icon><v-icon>mdi-pencil</v-icon></v-btn>
                                        <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-btn @click="delCalendar" icon><v-icon>mdi-dots-vertical</v-icon></v-btn>
                                    </v-toolbar>
                                    <v-card-text><span v-html="selectedEvent.details"></span></v-card-text>
                                    <v-card-actions><v-btn text color="secondary" @click="selectedOpen = false">Cancel</v-btn></v-card-actions>
                                </v-card>
                            </v-menu>
                        </v-sheet>
                        </v-col cols="2" align-self='center'>
                        <v-col justify="center"><v-date-picker v-model="picker"></v-date-picker>
                            <v-form ref="form" @submit.prevent="save" v-model="valid" lazy-validation>
                                <v-text-field id="content" name="content" type="text" v-model="content" variant="filled" label="내용" required></v-text-field>
                                <v-text-field id="money" name="money" type="text" v-model="money" label="금액" required></v-text-field>
                                    <a href="/calendar">
                                    <v-btn color="success" class="mr-4" @click="register">
                                        등록
                                    </v-btn>                   
                                    </a>
                            </v-form>
                        </v-col>
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
                type: 'month',
                focus: '',
                weekday: [0, 1, 2, 3, 4, 5, 6],
                value: '',
                events: [{
                    name:"dummy",
                    start:new Date(1999,0,1),
                    color:'red'
                }],
                colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
                picker: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                content:"",
                money:"",
                list:[],
                selectedEvent: {},
                selectedElement: null,
                selectedOpen: false,
                sum:0,
            },
            mounted () {
                this.$refs.calendar.checkChange()
            },
            methods: {
                viewDay ({ date }) {
                    this.focus = date
                },
                showEvent ({ nativeEvent, event }) {
                    const open = () => {
                        this.selectedEvent = event
                        this.selectedElement = nativeEvent.target
                        requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
                    }
                    if (this.selectedOpen) {
                        this.selectedOpen = false
                        requestAnimationFrame(() => requestAnimationFrame(() => open()))
                    } else {
                        open()
                    }
                    nativeEvent.stopPropagation()
                },
                getEventColor (event) {
                    return event.color
                },
                rnd (a, b) {
                    return Math.floor((b - a + 1) * Math.random()) + a
                },
                setToday () {
                    this.focus = ''
                },prev () {
                    this.$refs.calendar.prev()
                },
                next () {
                    this.$refs.calendar.next()
                },
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
                delCalendar:function() {
                    let event = this.selectedEvent;
                    // let date = event.start;
                    // let year = date.getFullYear();
                    // let month = date.getMonth();
                    // let day = date.getDate();
                    // let data={
                    //     content:event.content,
                    //         money:event.money,
                    //         year:year,
                    //         month:month,
                    //         day:day
                    // }
                    axios.post("/calendar/delete",event.id)
                    .then(res=>{console.log(res);})
                    location.href="/calendar";
                },
            },
            created() {
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                axios.get("/calendar/read")
                    .then(res => {
                        for (let item in res['data']){
                            let color = this.colors[this.rnd(0, this.colors.length - 1)]
                            this.events.push({
                                id:res['data'][item]['id'],
                                name:res['data'][item]['money'],
                                start:new Date(res['data'][item]['year'],res['data'][item]['month'],res['data'][item]['day']),
                                color:color,
                                details:res['data'][item]['content']
                            });
                            this.sum+=Number(res['data'][item]['money']);
                            this.list.push({
                                money:res['data'][item]['money'],
                                content:res['data'][item]['content'],
                                year:res['data'][item]['year'],
                                month:res['data'][item]['month'],
                                day:res['data'][item]['day'],
                                color:color
                            });
                        }
                    });
            }
        })
    </script>
</body>
</html>