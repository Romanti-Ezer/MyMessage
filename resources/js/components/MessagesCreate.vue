<template>
    <div>
        <h1 class='text-center mb-4'>Create Message</h1>

        <form class="container" @submit.prevent="submitForm">
            <div class="form-group">
                <label for="recipientEmail">Recipient E-mail</label>
                <input type="mail" class="form-control" id="recipientEmail" name="recipientEmail" placeholder="Enter recipient e-mail" autocorrect="off" autocapitalize="off" spellcheck="false"  @input="updateField($event)">
                <p class="text-danger mt-3" v-if="this.hasError('recipientEmail')">{{ errors.recipientEmail[0] }}</p>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" autocorrect="off"  @input="updateField($event)">
                <p class="text-danger mt-3" v-if="this.hasError('subject')">{{ errors.subject[0] }}</p>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="3" placeholder="Enter the content" autocorrect="off"  @input="updateField($event)"></textarea>
                <p class="text-danger mt-3" v-if="this.hasError('content')">{{ errors.content[0] }}</p>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="frequency">Frequency</label>
                    <select class="form-control" id="frequency" name="frequency"  @input="updateField($event)">
                        <option value="" selected disabled>Select a frequency</option>
                        <option value="only once">Only once</option>
                        <option value="daily">Daily</option>
                    </select>
                    <p class="text-danger mt-3" v-if="this.hasError('frequency')">{{ errors.frequency[0] }}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="startDate">Start Date</label>
                    <input type="date" class="form-control" id="startDate" name="startDate"  @input="updateField($event)">
                    <p class="text-danger mt-3" v-if="this.hasError('startDate')">{{ errors.startDate[0] }}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="expirationDate">Expiration Date</label>
                    <input type="date" class="form-control" id="expirationDate" name="expirationDate" @input="updateField($event)">
                    <p class="text-danger mt-3" v-if="this.hasError('expirationDate')">{{ errors.expirationDate[0] }}</p>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'CreateMessage',

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
                axios.post('/api/messages', this.form)
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