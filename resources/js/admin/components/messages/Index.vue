<template>
    <div class="container">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
            <h4 class="float-left pt-2">Messages</h4>
        </div>
        <div class="card-body px-0">
            <div class="row justify-content-between">
                <div class="col-7 col-md-5">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="fas fa-search"></i>
              </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="d-none d-sm-table-cell">
                        ID
                    </th>
                    <th class="d-none d-sm-table-cell">
                       User
                    </th>
                    <th>
                        Message
                    </th>
                    <th>
                        Actions
                    </th>

                </tr>
                </thead>
                <tbody>
                <tr v-for="(message, index) in drawMessages">
                    <td class="d-none d-sm-table-cell">{{message.id}}</td>
                    <td class="d-none d-sm-table-cell">{{message.user}}</td>
                    <td class="d-none d-sm-table-cell">{{message.message}}</td>
                    <td class="d-none d-sm-table-cell">
                        <span class="link good" @click="setStatus(2, message.id, index)">Одобрить</span>
                        <span class="link bad" @click="setStatus(3, message.id, index)">В топку</span>
                    </td>

                </tr>
                </tbody>
            </table>
    </div>
</template>


<script>
    export default {
        name: "index",
        data() {
            return {
                messages: [],
                loading: true
            }
        },
        mounted() {
            this.getMessages();
        },
        computed: {
          drawMessages() {
              return this.messages;
          }
        },
        methods: {
            getMessages() {
                this.loading = true;
                this.messages = [];

                axios.get(`api/messages/get`)
                    .then(response => {
                        this.messages = response.data.data;
                        delete response.data.data;
                        this.loading = false
                    })

            },
            setStatus(status, id, index) {
                axios.post(`api/messages/status/${id}`, {status: status})
                    .then(response => {
                        if (response.data === 1)
                           this.messages.splice(index, 1);
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .link {
        /*color: #5b5d80;*/
        cursor: pointer;
            &:hover {
                 color: #0e1380;
                 text-decoration: underline;
             }
    }
    .good {
        color: #1c7430;
    }
    .bad {
        color: #7b0000;
    }
</style>
