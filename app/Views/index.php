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
                <v-app-bar app="app" color="cyan" dark="dark">
                    <v-app-bar-title>
                        <v-btn outlined class="mr-4" color="grey darken-2" @click="calendar">Calendar</v-btn>
                    </v-app-bar-title>
                </v-app-bar>
                <v-main id="app">
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="2">
                                <v-spacer></v-spacer>
                            </v-col>
                        </v-row>

                        <v-data-table :headers="headers" :items="list" sort-by="num" class="elevation-1" :search='search'>
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Board</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialog" max-width="500px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn color="indigo" class="mr-2" v-bind="attrs" v-on="on" outlined icon>
                                                <v-icon>mdi-plus</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">{{ formTitle }}</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-text-field v-model="editedItem.author" label="author"></v-text-field>
                                                    </v-row>
                                                    <v-row>
                                                        <v-textarea filled auto-grow v-model="editedItem.content" label="content"></v-textarea>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="primary" text="text" @click="close">cancel</v-btn>
                                                <v-btn color="primary" text="text" @click="save">save</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-dialog v-model="dialogDelete" max-width="500">
                                        <v-card>
                                            <v-card-title></v-card-title>
                                            <v-card-text>해당 글을 삭제하실건가요?</v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="primary" text="text" @click="deleteItemConfirm">yes</v-btn>
                                                <v-btn color="primary" text="text" @click="closeDelete">no</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
                                <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
                            </template>
                        </v-data-table>
                    </v-container>
                </v-main>
            </v-app>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6/dist/vue.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vuetify@2.6.14/dist/vuetify.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            new Vue({
                el: "#app",
                vuetify: new Vuetify(),
                data: {
                    dialog: false,
                    dialogDelete: false,
                    message: "메인 게시판",
                    url: "",
                    arr: [],
                    search: '',
                    headers: [
                        {
                            text: '번호',
                            align: 'start',
                            value: 'num'
                        }, {
                            text: '내용',
                            value: 'content'
                        }, {
                            text: '작성자',
                            value: 'author'
                        }, {
                            text: '업데이트 시간',
                            value: 'update'
                        }, {
                            text: '작성 시간',
                            value: 'created'
                        }, {
                            text: '',
                            value: 'actions',
                            sortable: false
                        }
                    ],
                    editedIndex: -1,
                    editedItem: {
                        num: 0,
                        content: '',
                        author: '',
                        update: '',
                        created: '',
                        id: 0
                    },
                    defaultItem: {
                        num: 0,
                        content: '',
                        author: '',
                        update: '',
                        created: '',
                        id: -1
                    },
                    list: []
                },
                computed: {
                    formTitle() {
                        return this.editedIndex === -1
                            ? 'New Form'
                            : 'Edit Form'
                    }
                },

                watch: {
                    dialog(val) {
                        val || this.close()
                    },
                    dialogDelete(val) {
                        val || this.closeDelete()
                    }
                },
                methods: {
                    calendar() {
                        location.href = '/calendar'
                    },
                    delBoard: function (index) {
                        console.log("test");
                        console.log(index);
                        axios
                            .post("/board/delete", index)
                            .then(res => {
                                console.log(res);
                            })
                    },
                    editBoard: function (index) {
                        this.url = "/board/edit?id=" + index;
                        location.href = this.url;
                    },
                    editItem(item) {
                        this.editedIndex = this
                            .list
                            .indexOf(item)
                        this.editedItem = Object.assign({}, item)
                        this.dialog = true
                    },
                    deleteItem(item) {
                        this.editedIndex = this
                            .list
                            .indexOf(item)
                        this.editedItem = Object.assign({}, item)
                        this.dialogDelete = true
                        console.log("delete index = " + this.editedIndex)
                    },
                    deleteItemConfirm() {
                        axios
                            .post("/board/delete", this.editedItem['id'])
                            .then(res => {
                                console.log(res);
                            })
                        location.href = '/'
                    },
                    close() {
                        this.dialog = false
                        this.$nextTick(() => {
                            this.editedItem = Object.assign({}, this.defaultItem)
                            this.editedIndex = -1
                        })
                    },
                    closeDelete() {
                        this.dialogDelete = false
                        this.$nextTick(() => {
                            this.editedItem = Object.assign({}, this.defaultItem)
                            this.editedIndex = -1
                        })
                    },
                    save() {
                        if (this.editedIndex > -1) {
                            //수정할때
                            axios({
                                url: "/board/put?id=" + this.editedItem['id'],
                                method: 'post',
                                data: {
                                    author: this.editedItem['author'],
                                    content: this.editedItem['content']
                                },
                                header: {
                                    'Content-type': 'application/json'
                                }
                            })
                                .then(res => {
                                    console.log(res);
                                })
                                .catch(err => {
                                    console.log(err);
                                });

                        } else {
                            //생성할때
                            axios({
                                url: "/board/write",
                                method: 'post',
                                data: {
                                    author: this.editedItem['author'],
                                    content: this.editedItem['content']
                                },
                                header: {
                                    'Content-type': 'application/json'
                                }
                            })
                                .then(res => {
                                    console.log(res);
                                })
                                .catch(err => {
                                    console.log(err);
                                });
                        }
                        location.href = '/'
                    }
                },
                created() {
                    axios
                        .defaults
                        .headers
                        .common['X-Requested-With'] = 'XMLHttpRequest';
                    axios
                        .get("/board")
                        .then(res => {
                            for (let item in res['data']) {
                                this
                                    .arr
                                    .push(parseInt(item) + 1);
                                let num = parseInt(item) + 1;
                                this
                                    .list
                                    .push({num: num, id: res['data'][item]['id'],
                                        content: res['data'][item]['content'],
                                        author: res['data'][item]['author'],
                                        update: res['data'][item]['updatetime'],
                                        created: res['data'][item]['createtime']
                                    });
                            }
                            this
                                .arr
                                .reverse();
                            console.log(this.arr);
                            console.log(res['data']);
                        });
                }
            })
        </script>
    </body>
</html>