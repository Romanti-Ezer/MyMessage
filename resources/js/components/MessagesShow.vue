<template>
    <div class="container" v-if="this.message">
        <h1 class="text-center mb-4">Message #{{ this.message.message_id }}</h1>
        <div class="card position-relative">
            <div class="card-header d-flex flex-wrap justify-content-around align-items-center text-center">
                <p><b>Recipient</b><br/>{{ this.message.recipientEmail }}</p>
                <div>
                    <router-link :to="'/messages/' + message.message_id + '/edit'" class="btn btn-outline-primary" title="Edit Message" role="button">
                        <i class="fas fa-edit"></i>
                    </router-link>
                    <!-- :to="'/messages/' + message.message_id + '/delete'" -->
                    <a href="#" class="btn btn-outline-dark" title="Cancel Message" role="button" @click="cancelMessage">
                        <i class="fas fa-bell-slash"></i>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger" title="Delete Message" role="button" @click="modal = !modal">
                        <i class="fas fa-trash"></i>
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
                                <button type="button" class="btn btn-danger" @click="destroy">Delete</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="modal = !modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body text-center">
                <p><b>Subject</b></p>
                <h2 class="mb-3">{{ this.message.subject }}</h2>
                <p><b>Content</b></p>
                <p>{{ this.message.content }}</p>
            </div>
            <div class="card-footer d-md-flex justify-content-around align-items-center text-center">
                <div>
                    <p><b>Frequency</b></p>
                    <p>{{ this.message.frequency }}</p>
                </div>
                <div>
                    <p><b>Starts</b></p>
                    <p>{{ this.message.startDate }}</p>
                </div>
                <div>
                    <p><b>Expires</b></p>
                    <p>{{ this.message.expirationDate }}</p>
                </div>
                <div>
                    <p><b>Number of submissions</b></p>
                    <p>{{ this.message.submissionsNumber }}</p>
                </div>
                <p>Last update {{ this.message.last_updated }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'MessagesShow',
        
        mounted() {
            axios.get( '/api/messages/' + this.$route.params.id )
                .then(response => {
                    this.message = response.data.data;
                })
                .catch(error => {
                    if (error.response.status == 404) {
                        this.$router.push( '/messages' );
                    }
                });
        },

        data: function() {
            return {
                message: null,
                modal: false,
            }
        },

        methods: {
            cancelMessage: function() {
                axios.delete( '/api/messages/' + this.$route.params.id )
                    .then(response => {
                        this.$router.push( '/messages' );
                    })
                    .catch(errors => {
                        alert('Internal Error. Unable to delete message.');
                    });
            },
            destroy: function() {
                axios.delete( '/api/messages/' + this.$route.params.id + '/force' )
                    .then(response => {
                        this.$router.push( '/messages' );
                    })
                    .catch(errors => {
                        alert('Internal Error. Unable to delete message.');
                    });
            }
        }
    }
</script>

<style>

</style>