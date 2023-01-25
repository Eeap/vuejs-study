<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link
            href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900"
            rel="stylesheet">
        <link
            href="https://cdn.jsdelivr.net/npm/vuetify@2.6.14/dist/vuetify.min.css"
            rel="stylesheet">
        <link
            href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css"
            rel="stylesheet">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"/>
        <link
            rel="stylesheet"
            href="https://uicdn.toast.com/chart/latest/toastui-chart.min.css"/>

    </head>
    <body>
        <div id="app">
            <v-app>
                <v-navigation-drawer
                    permanent
                    expand-on-hover
                    app>
                    <v-list>
                        <v-list-item class="px-2">
                            <v-list-item-avatar>
                                <v-icon>fas fa-user</v-icon>
                            </v-list-item-avatar>
                        </v-list-item>

                        <v-list-item link>
                            <v-list-item-content>
                                <v-list-item-title class="text-h6">
                                    Eeap
                                </v-list-item-title>
                                <v-list-item-subtitle>sumink0903@gmail.com</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list nav dense>
                        <v-list-item href="/" link>
                            <v-list-item-icon>
                                <v-icon>fas fa-house</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>home</v-list-item-title>
                        </v-list-item>
                        <v-list-item href="/calendar" link>
                            <v-list-item-icon>
                                <v-icon>fas fa-calendar</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>calendar</v-list-item-title>
                        </v-list-item>
                        <v-list-item href='/board' link>
                            <v-list-item-icon>
                                <v-icon>fas fa-square-poll-horizontal</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>board</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>

                <v-app-bar>
                    <v-app-bar-title></v-app-bar-title>
                </v-app-bar>
                <v-main app>
                    <v-container fluid>
                        <div id="chart-area"></div>
                    </v-container>
                </v-main>

                <v-footer app></v-footer>
            </v-app>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/vue@2.6/dist/vue.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vuetify@2.6.14/dist/vuetify.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://uicdn.toast.com/chart/latest/toastui-chart.min.js"></script>
        <script>

            new Vue({
                el: "#app",
                vuetify: new Vuetify(),
                data: {
                    dayList:[],
                    payData: {
                        name:"지출",
                        data:[0,0,0,0,0,0,0,0,0,0,0,0]
                    },
                    revenueData: {
                        name:"수익",
                        data:[0,0,0,0,0,0,0,0,0,0,0,0]
                    }
                },
                computed: {},
                mounted() {
                },
                methods: {
                    lineChart() {
                        const Chart = toastui.Chart
                        const el = document.getElementById('chart-area')
                        const data = {
                            categories: this.dayList,
                            series: [
                                this.payData,
                                this.revenueData
                            ]
                        }
                        const options = {
                            chart: {
                                title: '월별 금액',
                                width: 1500,
                                height: 500
                            },
                            xAxis: {
                                title: 'Month',
                                pointOnColumn: true,
                                date: {
                                    format: 'YY-MM-DD'
                                }
                            },
                            yAxis: {
                                title: '금액'
                            },
                            tooltip: {
                                formatter: (value) => `${value}원`
                            },
                            legend: {
                                align: 'bottom'
                            }
                        }

                        const chart = Chart.lineChart({el, data, options})
                    }

                },
                created() {
                    let today = new Date()
                    let year = today.getFullYear()
                    let month = today.getMonth()
                    for (let i=12;i>0; i--) {
                        if (month==-1) {
                            year-=1
                            month = 11
                        }
                        let temp = parseInt(month+1)+"/01/"+year
                        this.dayList.push(temp)
                        month-=1
                    }
                    this.dayList.reverse()

                    axios
                        .defaults
                        .headers
                        .common['X-Requested-With'] = 'XMLHttpRequest'
                    axios
                        .get("/calendar/read")
                        .then(res => {
                            for (let item in res['data']) {
                                for (let i=0;i<12;i++) {
                                    let findItemDate = new Date(this.dayList[i])
                                    let findYear = findItemDate.getFullYear()
                                    let findMonth = findItemDate.getMonth()
                                    if (findMonth==-1) {
                                        findMonth=11
                                    }
                                    console.log("year = "+res['data'][item]['year']+" findyear = "+findYear)
                                    if (findYear == res['data'][item]['year']) {
                                        console.log("month = "+res['data'][item]['month']+" findmonth = "+findMonth)
                                        if (findMonth == res['data'][item]['month']) {
                                            if (res['data'][item]['category'] == "지출") {
                                                this.payData['data'][i]+=parseInt(res['data'][item]['money'])
                                            } else {
                                                this.revenueData['data'][i]+=parseInt(res['data'][item]['money'])
                                            }
                                         }
                                    }
                                }
                            }
                            this.lineChart()
                            console.log(this.payData)
                            console.log(this.revenueData)

                        })
                }
            })
            
        </script>
    </body>
</html>