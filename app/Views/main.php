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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://uicdn.toast.com/chart/latest/toastui-chart.min.css" />
        <script src="https://uicdn.toast.com/chart/latest/toastui-chart.min.js"></script>
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
                        <v-list-item link>
                            <v-list-item-icon>
                                <v-icon>mdi-star</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>Starred</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>

                <v-app-bar>
                    <v-app-bar-title>
                    </v-app-bar-title>
                </v-app-bar>
                <v-main>
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
        <script>
            
        </script>
    </body>
</html>