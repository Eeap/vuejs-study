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
                <v-toolbar-title><v-btn outlined class="mr-4" color="grey darken-2" @click="main">Home</v-btn></v-toolbar-title>
            </v-app-bar>
            <v-main id="app">
                <v-container>
                    <v-row class='fill-height'>
                        <v-col cols="8">
                        <v-sheet height="64">
                            <v-toolbar flat>
                                <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">Today</v-btn>
                                <v-btn fab text small color="grey darken-2" @click="prev"><v-icon small>mdi-chevron-left</v-icon></v-btn>
                                <v-btn fab text small color="grey darken-2" @click="next"><v-icon small>mdi-chevron-right</v-icon></v-btn>
                                <v-toolbar-title v-if="$refs.calendar">{{ $refs.calendar.title }}</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-title>
                                    <div class="text-center">
                                        <v-dialog v-model="dialogPayment" max-width="500px">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn color="indigo" class="mr-2" v-bind="attrs" v-on="on" outlined icon>
                                                    <v-icon>mdi-plus</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-card>
                                                <v-card-title color="cyan">
                                                    <span class="text-h5" align="center" justify="center">정기 항목 등록</span>
                                                </v-card-title>
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-container>
                                                        <v-form ref="form" @submit.prevent="save" lazy-validation>

                                                            <v-row>
                                                                <v-col cols="12" lg="6">
                                                                    <v-menu ref="menu" v-model="menuStart" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="auto">
                                                                        <template v-slot:activator="{ on, attrs }">
                                                                            <v-text-field v-model="dateStart" label="Start Date" persistent-hint prepend-icon="mdi-calendar" v-bind="attrs" v-on="on"></v-text-field>
                                                                        </template>
                                                                        <v-date-picker v-model="dateStart" no-title @input="menuStart = false"></v-date-picker>
                                                                    </v-menu>
                                                                </v-col>
                                                                <v-col cols="12" lg="6">
                                                                    <v-menu ref="menu" v-model="menuLast" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="auto">
                                                                        <template v-slot:activator="{ on, attrs }">
                                                                            <v-text-field v-model="dateLast" label="Last Date" persistent-hint prepend-icon="mdi-calendar" v-bind="attrs" v-on="on">
                                                                            </v-text-field>
                                                                        </template>
                                                                        <v-date-picker v-model="dateLast" no-title @input="menuLast = false"></v-date-picker>
                                                                    </v-menu>
                                                                </v-col>
                                                            </v-row>
                                                            <v-text-field type="text" v-model="regularDay" variant="filled" label="정기 날짜" required outlined></v-text-field>
                                                            <v-text-field id="content" name="regularContent" type="text" v-model="regularContent" variant="filled" label="내용" required outlined></v-text-field>
                                                            <v-currency-field v-model="regularMoney" label="금액" filled outlined :decimal-length=0></v-currency-field>
                                                            <v-select :items="categories" v-model="category" label="항목" dense outlined></v-select>
                                                        </v-form>
                                                    </v-container>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="primary" class="mr-4" @click="registerRegular()" icon>
                                                        <v-icon dark right>mdi-checkbox-marked-circle</v-icon>
                                                    </v-btn>
                                                    <v-btn color="primary" text @click="dialogPayment = false,clearEvent()">
                                                        cancel
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>

                                        <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-x>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn outlined class="mr-4" color="grey darken-2" v-bind="attrs" v-on="on"><v-icon medium>mdi-arrow-up-bold-box-outline</v-icon></v-btn>
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
                                                        <v-list-item-title>지출 금액 : {{ minus }}원</v-list-item-title>
                                                    </v-list-item>

                                                    <v-list-item>
                                                        <v-list-item-title>수익 금액 : {{ plus }}원</v-list-item-title>
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
                                                <v-card-text>해당 항목을 삭제하실건가요?</v-card-text>
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
                                        <v-currency-field v-model="selectedEvent.money" label="금액" filled outlined :decimal-length=0 :readonly="readonly"></v-currency-field>
                                        <v-select :items="categories" v-model="selectedEvent.category" label="항목" dense outlined :readonly="readonly"></v-select>
                                        <v-spacer></v-spacer>
                                        <v-row align="center" justify="end">
                                            <v-btn color="primary" class="mr-4" :disabled="disabled" @click="editCalendar" icon>
                                                <v-icon dark right>mdi-checkbox-marked-circle</v-icon>
                                            </v-btn>
                                            <v-card-actions>
                                                <v-btn text color="secondary" @click="selectedOpen = false,disabled = true, readonly=true,reload()">
                                                    Cancel</v-btn>
                                            </v-card-actions>
                                        </v-row>
                                    </v-card-text>
                                    
                                </v-card>
                            </v-menu>
                        </v-sheet>
                        </v-col>
                        <v-col cols="auto">
                        <v-card>
                            <v-card-title></v-card-title>
                                <v-date-picker v-model="picker"></v-date-picker>
                            <v-divider></v-divider>
                            <v-spacer></v-spacer>
                            <v-form ref="form" @submit.prevent="save" lazy-validation>
                                <v-text-field id="content" name="content" type="text" v-model="content" variant="filled" label="내용" required outlined></v-text-field>
                                <v-currency-field v-model="money" label="금액" filled outlined :decimal-length=0></v-currency-field>
                                <v-select :items="categories" v-model="category" label="항목" dense outlined></v-select>
                                    <v-row align="center" justify="end">
                                        <v-btn color="primary" class="mr-4" @click="register(),reload()" icon>
                                            <v-icon dark right>mdi-checkbox-marked-circle</v-icon>
                                        </v-btn> 
                                    </v-row>                
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
    <script src="https://unpkg.com/v-currency-field"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                regularContent:'',
                regularMoney:'',
                regularDay:'',
                dateStart: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                dateLast: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                dateFormattedStart: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                dateFormattedLast: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                menuStart: false,
                menuLast: false,
                categories:['지출','수익'],
                dialogPayment:false,
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
                picker: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                content:"",
                money:"",
                list:[],
                selectedEvent: {},
                selectedElement: null,
                selectedOpen: false,
                sum:0,
                dialog:false,
                plus:0,
                minus:0,
            },
            mounted () {
                this.$refs.calendar.checkChange()
            },
            methods: {
                clearEvent() {
                    this.content=""
                    this.money=""
                    this.dateLast= (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)
                    this.dateStart = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)
                },
                moreEvent() {
                    console.log("more = "+this.$refs.calendar.moreEvents)
                },
                calc(idx) {
                    this.sum=0
                    this.plus=0
                    this.minus=0
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
                                if (items[i]['category']=="지출") {
                                    this.minus+=parseInt(items[i]['money'])
                                } else {
                                    this.plus+=parseInt(items[i]['money'])
                                }
                                this.sum+=parseInt(items[i]['money'])
                            }
                        }
                    }
                    this.sum=this.plus-this.minus
                    this.sum = this.valueComma(this.sum)
                    this.plus = this.valueComma(this.plus)
                    this.minus = this.valueComma(this.minus)
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
                            money:event.money,
                            content:event.details,
                            year:year,
                            month:month,
                            day:day,
                            category:event.category,
                        },
                        header:{ 'Content-type': 'application/json'}
                    })
                    .then(res => {
                        console.log(res);
                    }).catch(err =>{console.log(err);});
                    location.href='/calendar'
                },
                main() {
                    location.href='/'
                },
                valueComma(val) {
                    return String(parseInt(val)).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                },
                formatDate (date) {
                    if (!date) return null
                    const [year, month, day] = date.split('-')
                    return `${month}/${day}/${year}`
                },
                registerAPI(content,money,year,month,day,category) {
                    axios({
                        url:"/calendar/write",
                        method:'post',
                        data:{
                            content:content,
                            money:money,
                            year:year,
                            month:month,
                            day:day,
                            category:category
                        },
                        header:{ 'Content-type': 'application/json'}
                    })
                    .then(res => {
                        console.log(res);
                    }).catch(err =>{console.log(err);});
                },
                registerRegular() {
                    let startDate =new Date(this.dateStart);
                    let lastDate = new Date(this.dateLast);
                    
                    console.log(startDate.getFullYear());
                    console.log(lastDate.getFullYear());
                    let lastYear = lastDate.getFullYear()
                    let startYear = startDate.getFullYear()
                    let startMonth = startDate.getMonth()
                    let lastMonth = lastDate.getMonth()
                    let check = false
                    for (let y=startYear;y<lastYear;y++) {
                        if ((lastYear-startYear)==y){
                            break;
                        }
                        else {
                            check = true
                            for (let m=startMonth;m<12;m++) {
                                this.registerAPI(this.regularContent,this.regularMoney,y,m,this.regularDay,this.category)
                            }
                        }
                    }
                    if (check) {
                        //1월부터 시작 1월은 0을 의미
                        for (let m=0;m<lastMonth+1;m++) {
                            this.registerAPI(this.regularContent,this.regularMoney,lastYear,m,this.regularDay,this.category)
                        }
                    }
                    else {
                        for (let m=startMonth;m<lastMonth+1;m++) {
                            this.registerAPI(this.regularContent,this.regularMoney,lastYear,m,this.regularDay,this.category)
                        }
                    }
                    location.href='/calendar'

                    // axios({
                    //     url:"/calendar/write",
                    //     method:'post',
                    //     data:{
                    //         content:this.content,
                    //         money:this.money,
                    //         year:year,
                    //         month:month,
                    //         day:day,
                    //         category:this.category
                    //     },
                    //     header:{ 'Content-type': 'application/json'}
                    // })
                    // .then(res => {
                    //     console.log(res);
                    // }).catch(err =>{console.log(err);});
                }
            },
            watch: {
                events: function (newVal, oldVal) {
                    console.log("watch 실행!")
                    this.calc(0)
                },
                date (val) {
                    this.dateFormattedStart = this.formatDate(this.dateStart)
                    this.dateFormattedLast = this.formatDate(this.dateLast)
                },
            },
            created() {
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                axios.get("/calendar/read")
                    .then(res => {
                        for (let item in res['data']){
                            let color = "deep-orange lighten-1"
                            let times = new Date(res['data'][item]['time'])
                            let hour = times.getHours()
                            let minute = times.getMinutes()
                            let seconds = times.getSeconds()
                            let name = this.valueComma(res['data'][item]['money'])
                            if (res['data'][item]['category']=="수익") {
                                color="light-blue accent-2"
                            }
                            this.events.push({
                                id:res['data'][item]['id'],
                                name:name,
                                start:new Date(res['data'][item]['year'],res['data'][item]['month'],res['data'][item]['day'],hour,minute,seconds),
                                color:color,
                                details:res['data'][item]['content'],
                                timed:true,
                                times:times,
                                category:res['data'][item]['category'],
                                money:res['data'][item]['money']
                            });
                        }
                    });
            }
        })
    </script>
</body>
</html>