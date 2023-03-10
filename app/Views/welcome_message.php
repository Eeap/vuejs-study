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
                    <v-row class='fill-height'>
                        <v-col cols="9">
                        <v-sheet height="64">
                            <v-toolbar flat>
                                <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">Today</v-btn>
                                <v-btn fab text small color="grey darken-2" @click="prev"><v-icon small>mdi-chevron-left</v-icon></v-btn>
                                <v-btn fab text small color="grey darken-2" @click="next"><v-icon small>mdi-chevron-right</v-icon></v-btn>
                                <v-toolbar-title v-if="$refs.calendar">{{ $refs.calendar.title }}</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-title>
                                    <div class="text-center">
                                        <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-x>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn outlined class="mr-4" color="grey darken-2" v-bind="attrs" v-on="on">총 금액</v-btn>
                                            </template>

                                            <v-card>
                                                <v-list>
                                                    <v-list-item>
                                                        <v-list-item-content>
                                                            <v-list-item-title>총 금액</v-list-item-title>
                                                            <v-list-item-subtitle>{{sum}}원</v-list-item-subtitle>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </v-list>
                                                <v-divider></v-divider>

                                                <v-list>
                                                    <v-list-item>
                                                        <v-list-item-title>지출 금액 : {{ sum }}</v-list-item-title>
                                                    </v-list-item>

                                                    <v-list-item>
                                                        <v-list-item-title>수익 금액 : {{ sum }}</v-list-item-title>
                                                    </v-list-item>
                                                </v-list>

                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn text @click="menu = false">Cancel</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-menu>
                                    </div>

                                </v-toolbar-title>
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
                            @change="change"
                            ></v-calendar>
                            <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
                                <v-card color="grey lighten-4" min-width="350px" flat>
                                    <v-toolbar :color="selectedEvent.color" dark>
                                        <v-btn icon @click="editEvent"><v-icon>mdi-pencil</v-icon></v-btn>
                                        <v-toolbar-title v-html="selectedEvent.times"></v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-dialog v-model="dialog" width="500">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn color="red lighten-2" icon dark v-bind="attrs" v-on="on">
                                                    <v-icon>mdi-delete</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-card>
                                                <v-card-title></v-card-title>
                                                <v-card-text>삭제하실건가요?</v-card-text>
                                                <v-divider></v-divider>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="primary" text @click="dialog = false,delCalendar()">
                                                        yes
                                                    </v-btn>
                                                    <v-btn color="primary" text @click="dialog = false">
                                                        no
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-text-field type="text" v-model="selectedEvent.details" label="내용" :readonly="readonly" outlined></v-text-field>
                                        <v-text-field type="text" v-model="selectedEvent.name" label="금액" :readonly="readonly" outlined></v-text-field>
                                        <v-btn :disabled="disabled" @click="editCalendar" icon><v-icon dark right>mdi-checkbox-marked-circle</v-icon></v-btn>
                                </v-card-text>
                                    <v-card-actions><v-btn text color="secondary" @click="selectedOpen = false,disabled = true, readonly=true,reload()">Cancel</v-btn></v-card-actions>
                                </v-card>
                            </v-menu>
                        </v-sheet>
                        </v-col>
                        <v-col cols="2">
                        <v-card>
                            <v-card-title></v-card-title>
                                <v-date-picker v-model="picker"></v-date-picker>
                            <v-divider></v-divider>
                            <v-spacer></v-spacer>
                            <v-form ref="form" @submit.prevent="save" v-model="valid" lazy-validation>
                                <v-text-field id="content" name="content" type="text" v-model="content" variant="filled" label="내용" required outlined></v-text-field>
                                <v-text-field id="money" name="money" type="text" v-model="money" label="금액" required outlined></v-text-field>
                                <v-select :items="categories" v-model="category" label="항목" dense outlined></v-select>
                                    <a href="/calendar">
                                    <v-btn color="success" class="mr-4" @click="register">
                                        등록
                                    </v-btn>                   
                                    </a>
                            </v-form>
                        </v-card>
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
                categories:['지출','수익'],
                category:"지출",
                menu: false,
                readonly:true,
                disabled:true,
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
                dialog:false
            },
            mounted () {
                this.$refs.calendar.checkChange()
            },
            methods: {
                moreEvent() {
                    console.log("more = "+this.$refs.calendar.moreEvents)
                },
                calc(idx) {
                    this.sum=0
                    let str = this.$refs.calendar.title
                    let arr = str.split(' ')
                    var month=""
                    let year=parseInt(arr[1])
                    console.log(this.events)
                    for (let i=0;i<arr[0].length;i++) {
                        if (arr[0][i]!="월") {
                            month+=arr[0][i]
                        }
                    }
                    month = parseInt(month)+idx;
                    if (month===0) {
                        month=12
                        year-=1
                    } else if (month==13){
                        month=1
                        year+=1
                    }
                    console.log("month = "+month)
                    console.log("year = "+ year)
                    let items = this.events
                    for (let i=0;i<items.length;i++) {
                        let date = new Date(items[i]['start'])
                        if (date.getFullYear()==parseInt(year)) {
                            console.log(date.getMonth()+1)
                            if (date.getMonth()+1==month) {
                                this.sum+=parseInt(items[i]['name'])
                            }
                        }
                    }
                    console.log("sum = "+this.sum)
                },
                change() {
                    this.calc(0)
                },
                reload () {
                    location.href='/calendar'
                },
                viewDay ({ date }) {
                    this.focus = date
                    this.type = 'day'
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
                    this.readonly=true
                    this.disabled=true
                },
                editEvent:function() {
                    this.readonly=false
                    this.disabled=false
                },
                getEventColor (event) {
                    return event.color
                },
                rnd (a, b) {
                    return Math.floor((b - a + 1) * Math.random()) + a
                },
                setToday () {
                    this.focus = ''
                    this.type='month'
                },prev () {
                    this.$refs.calendar.prev()
                    this.calc(-1)
                },
                next () {
                    this.$refs.calendar.next()
                    this.calc(1)
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
                            day:day,
                            category:this.category
                        },
                        header:{ 'Content-type': 'application/json'}
                    })
                    .then(res => {
                        console.log(res);
                    }).catch(err =>{console.log(err);});
                },
                delCalendar:function() {
                    let event = this.selectedEvent;
                    axios.post("/calendar/delete",event.id)
                    .then(res=>{console.log(res);})
                    location.href="/calendar";
                },
                formPage:function(){
                    location.href="/calendar/form"
                },
                editCalendar:function(){
                    let event = this.selectedEvent;
                    let temp =new Date(event.start);
                    let year = temp.getFullYear();
                    let month = temp.getMonth();
                    let day = temp.getDate();
                    axios({
                        url:"/calendar/edit?id="+event.id,
                        method:'post',
                        data:{
                            id:event.id,
                            money:event.name,
                            content:event.details,
                            year:year,
                            month:month,
                            day:day,
                        },
                        header:{ 'Content-type': 'application/json'}
                    })
                    .then(res => {
                        console.log(res);
                    }).catch(err =>{console.log(err);});
                    location.href='/calendar'
                },
            },
            watch: {
                events: function (newVal, oldVal) {
                    console.log("watch 실행!")
                    this.calc(0)
                }
            },
            created() {
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                axios.get("/calendar/read")
                    .then(res => {
                        for (let item in res['data']){
                            let color = this.colors[this.rnd(0, this.colors.length - 1)]
                            let times = new Date(res['data'][item]['time'])
                            let hour = times.getHours()
                            let minute = times.getMinutes()
                            let seconds = times.getSeconds()
                            this.events.push({
                                id:res['data'][item]['id'],
                                name:res['data'][item]['money'],
                                start:new Date(res['data'][item]['year'],res['data'][item]['month'],res['data'][item]['day'],hour,minute,seconds),
                                color:color,
                                details:res['data'][item]['content'],
                                timed:true,
                                times:times
                            });
                            this.list.push({
                                id:res['data'][item]['id'],
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