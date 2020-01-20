<template>
    <div>
        <h1 class='text-center mb-4'>Messages</h1>
        <div class="container" v-if="messages && messages.length === 0">
            <p>No messages yet. <router-link to="/messages/create">Get Started.</router-link></p>
        </div>
        <div class="container" v-else>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">Subject</th>
                        <th scope="col">Start date</th>
                        <th scope="col">Expiration date</th>
                        <th scope="col">Recipient</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="message in messages" v-bind:key="message.data.message_id">
                        <td>{{ message.data.subject }}</td>
                        <td>{{ message.data.startDate }}</td>
                        <td>{{ message.data.expirationDate }}</td>
                        <td>{{ message.data.recipientEmail }}</td>
                        <td>
                            <div>
                                <router-link :to="message.links.self" class="btn btn-outline-success" title="View Message" role="button">
                                    <i class="fas fa-eye"></i>
                                </router-link>
                                <router-link :to="'/messages/' + message.data.message_id + '/edit'" class="btn btn-outline-primary" title="Edit Message" role="button">
                                    <i class="fas fa-edit"></i>
                                </router-link>
                                <a href="javascript:void(0)" class="btn btn-outline-dark" title="Cancel Message" role="button" @click="cancelMessage(message.data.message_id)">
                                    <i class="fas fa-bell-slash"></i>
                                </a>

                                <div class="modal-dialog position-absolute" role="document" v-if="modal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Message</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="modal = !modal">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this record?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" @click="destroy(message.data.message_id)">Delete</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="modal = !modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class='text-center mb-4'>Canceled Messages</h2>
        <div class="container" v-if="messagesDeleted && messagesDeleted.length === 0">
            <p>No canceled messages.</p>
        </div>
        <div class="container" v-else>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">Subject</th>
                        <th scope="col">Start date</th>
                        <th scope="col">Expiration date</th>
                        <th scope="col">Recipient</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="message in messagesDeleted" :key="message.data.message_id">
                        <td>{{ message.data.subject }}</td>
                        <td>{{ message.data.startDate }}</td>
                        <td>{{ message.data.expirationDate }}</td>
                        <td>{{ message.data.recipientEmail }}</td>
                        <td>
                            <div>
                                <a href="javascript:void(0)" class="btn btn-outline-success" title="Restore Message" role="button" @click="restoreMessage(message.data.message_id)">
                                    <i class="fas fa-trash-restore"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MessagesIndex',

    mounted() {
        axios.get('/api/messages')
            .then(response => {
                this.messages = response.data.data;
            })
            .catch(error => {
                alert('Unable to fetch messages');
            });
        axios.get('/api/messages/deleted')
            .then(response => {
                this.messagesDeleted = response.data.data;
            })
            .catch(error => {
                alert('Unable to fetch canceled messages');
            });
    },

    data: function() {
        return {
            messages: null,
            messagesDeleted: null,
            modal: false,
        }
    },

    methods: {
        cancelMessage: function(message_id) {
            axios.delete( '/api/messages/' + message_id )
                .then(response => {
                    this.forceUpdate();
                })
                .catch(errors => {
                    alert('Internal Error. Unable to delete message.');
                });
        },
        restoreMessage: function(message_id) {
            axios.patch( '/api/messages/' + message_id + '/restore' )
                .then(response => {
                    this.forceUpdate();
                })
                .catch(errors => {
                    alert('Internal Error. Unable to delete message.');
                });
        },
        destroy: function(message_id) {
            axios.delete( '/api/messages/' + message_id + '/force' )
                .then(response => {
                    this.forceUpdate();
                })
                .catch(errors => {
                    alert('Internal Error. Unable to delete message.');
                });
        },
        forceUpdate() {
            axios.get('/api/messages')
                .then(response => {
                    this.messages = response.data.data;
                })
                .catch(error => {
                    alert('Unable to fetch messages');
                });
            axios.get('/api/messages/deleted')
                .then(response => {
                    this.messagesDeleted = response.data.data;
                })
                .catch(error => {
                    alert('Unable to fetch canceled messages');
                });
        }
    },
}
</script>

<style>

</style>