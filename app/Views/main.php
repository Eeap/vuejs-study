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
                                <v-icon>mdi-star</v-icon>
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
                        <v-row id="chart-area"></v-row>
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
                data: {},
                computed: {},
                methods: {},
                created() {
                    
                    // axios     .defaults     .headers     .common['X-Requested-With'] =
                    // 'XMLHttpRequest';     axios.get("/calendar/read") .then(res => {     for (let
                    // item in res['data']){         let color = "deep-orange lighten-1"         let
                    // times = new Date(res['data'][item]['time'])         let hour =
                    // times.getHours()         let minute = times.getMinutes()         let seconds
                    // = times.getSeconds()         let name =
                    // this.valueComma(res['data'][item]['money'])         if
                    // (res['data'][item]['category']=="수익") {             color="light-blue
                    // accent-2"         }         this.events.push({ id:res['data'][item]['id'],
                    // name:name,             start:new
                    // Date(res['data'][item]['year'],res['data'][item]['month'],res['data'][item]['day'],hour,minute,seconds),
                    // color:color,             details:res['data'][item]['content'], timed:true,
                    // times:times, category:res['data'][item]['category'],
                    // money:res['data'][item]['money']         });     } });
                }
            })
            const Chart = toastui.Chart;
            const el = document.getElementById('chart-area');
            const data = {
                categories: [
                    '01/01/2020',
                    '02/01/2020',
                    '03/01/2020',
                    '04/01/2020',
                    '05/01/2020',
                    '06/01/2020',
                    '07/01/2020',
                    '08/01/2020',
                    '09/01/2020',
                    '10/01/2020',
                    '11/01/2020',
                    '12/01/2020'
                ],
                series: [
                    {
                        name: 'Seoul',
                        data: [
                            -3.5,
                            -1.1,
                            4.0,
                            11.3,
                            17.5,
                            21.5,
                            25.9,
                            27.2,
                            24.4,
                            13.9,
                            6.6,
                            -0.6
                        ]
                    }, {
                        name: 'Seattle',
                        data: [
                            3.8,
                            5.6,
                            7.0,
                            9.1,
                            12.4,
                            15.3,
                            17.5,
                            17.8,
                            15.0,
                            10.6,
                            6.6,
                            3.7
                        ]
                    }, {
                        name: 'Sydney',
                        data: [
                            22.1,
                            22.0,
                            20.9,
                            18.3,
                            15.2,
                            12.8,
                            11.8,
                            13.0,
                            15.2,
                            17.6,
                            19.4,
                            21.2
                        ]
                    }, {
                        name: 'Moscow',
                        data: [
                            -10.3,
                            -9.1,
                            -4.1,
                            4.4,
                            12.2,
                            16.3,
                            18.5,
                            16.7,
                            10.9,
                            4.2,
                            -2.0,
                            -7.5
                        ]
                    }, {
                        name: 'Jungfrau',
                        data: [
                            -13.2,
                            -13.7,
                            -13.1,
                            -10.3,
                            -6.1,
                            -3.2,
                            0.0,
                            -0.1,
                            -1.8,
                            -4.5,
                            -9.0,
                            -10.9
                        ]
                    }
                ]
            };
            const options = {
                chart: {
                    title: '24-hr Average Temperature',
                    width: 1000,
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
                    title: 'Amount'
                },
                tooltip: {
                    formatter: (value) => `${value}°C`
                },
                legend: {
                    align: 'bottom'
                }
            };

            const chart = Chart.lineChart({el, data, options});
        </script>
    </body>
</html>