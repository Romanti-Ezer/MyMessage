<template>
    <div>
        <h1 class='text-center mb-4'>Edit Message #{{ this.$route.params.id }}</h1>

        <form class="container" @submit.prevent="submitForm">
            <div class="form-group">
                <label for="recipientEmail">Recipient E-mail</label>
                <input type="mail" class="form-control" id="recipientEmail" name="recipientEmail" placeholder="Enter recipient e-mail" autocorrect="off" autocapitalize="off" spellcheck="false"  @input="updateField($event)" v-model="form.recipientEmail">
                <p class="text-danger mt-3" v-if="this.hasError('recipientEmail')">{{ errors.recipientEmail[0] }}</p>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" autocorrect="off"  @input="updateField($event)" v-model="form.subject">
                <p class="text-danger mt-3" v-if="this.hasError('subject')">{{ errors.subject[0] }}</p>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="3" placeholder="Enter the content" autocorrect="off"  @input="updateField($event)" v-model="form.content"></textarea>
                <p class="text-danger mt-3" v-if="this.hasError('content')">{{ errors.content[0] }}</p>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="frequency">Frequency</label>
                    <select class="form-control" id="frequency" name="frequency"  @input="updateField($event)" v-model="form.frequency">
                        <option value="" selected disabled>Select a frequency</option>
                        <option value="only once">Only once</option>
                        <option value="daily">Daily</option>
                    </select>
                    <p class="text-danger mt-3" v-if="this.hasError('frequency')">{{ errors.frequency[0] }}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="startDate">Start Date</label>
                    <input type="date" class="form-control" id="startDate" name="startDate"  @input="updateField($event)" v-model="form.startDate">
                    <p class="text-danger mt-3" v-if="this.hasError('startDate')">{{ errors.startDate[0] }}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="expirationDate">Expiration Date</label>
                    <input type="date" class="form-control" id="expirationDate" name="expirationDate" @input="updateField($event)" v-model="form.expirationDate">
                    <p class="text-danger mt-3" v-if="this.hasError('expirationDate')">{{ errors.expirationDate[0] }}</p>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'MessageEdit',

        mounted() {
            axios.get( '/api/messages/' + this.$route.params.id )
                .then(response => {
                    this.form = response.data.data;
                    this.form.frequency = this.form.frequency.toLowerCase();
                    this.form.startDate = new Date(this.form.startDate).toISOString().substring(0, 10);
                    this.form.expirationDate = new Date(this.form.expirationDate).toISOString().substring(0, 10);
                })
                .catch(error => {
                    if (error.response.status == 404) {
                        this.$router.push( '/messages' );
                    }
                });
        },

        data: function() {
            return {
                form: {
                    'recipientEmail': '',
                    'subject': '',
                    'content': '',
                    'frequency': '',
                    'startDate': '',
                    'expirationDate': '',
                },

                errors: null,
            }
        },

        methods: {
            updateField: function($event) {
                let field = $event.target.name;
                if (this.errors)
                    this.errors[field] = null;
                this.form[field] = $event.target.value;
            },
            hasError: function(field) {
                return this.errors && field in this.errors && this.errors[field] && this.errors[field][0];
            },
            submitForm: function() {
                axios.patch('/api/messages/' + this.$route.params.id, this.form)
                    .then(response => {
                        this.$router.push( response.data.links.self );
                    })
                    .catch(errors => {
                        this.errors = errors.response.data.errors;
                    });
            }
        },
    }
</script>

<style>

</style>