<template>
    <div id="emailComposer">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-link" @click="cancelCompose">Cancel</button>
            </div>
            <div class="card-body">
                <h5 class="card-title">Compose New Email</h5>
                <div class="form-group">
                    <label class="col-12">From: <strong v-html="getFromName"></strong></label>
                </div>
                <div class="form-group">
                    <label for="recepients" class="col-12">To:</label>
                    <to-recipients-container
                        :recipients.sync="email.recipients"
                        @remove-recipient="removeRecipient"
                        @add-recipient="addRecipient"
                    ></to-recipients-container>
                </div>
                <div class="form-group">
                    <label for="subject" class="col-12">Subject</label>
                    <div class="col-12">
                        <input type="text" name="subject" class="form-control" v-model="email.subject">
                    </div>
                </div>
                <div class="form-group">
                    <label for="body_html" class="col-12">Body (HTML)</label>
                    <div class="col-12">
                        <textarea  name="body_html" class="form-control" v-model="email.body_html"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="body_text" class="col-12">Body (Plain Text)</label>
                    <div class="col-12">
                        <textarea  name="body_text" class="form-control" v-model="email.body_text"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-12">Attachments</label>
                    <div class="col-12">
                        <div class="large-12 medium-12 small-12 cell">
                            <input type="file" id="files" ref="files" multiple v-on:change="pushFile()"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12 text-center">
                        <button v-show="hasRecipients" class="btn btn-success" @click="sendEmail">Send Email</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import ToRecipientsContainer from "./ToRecipientsContainer";
 export default {
    name : "EmailCompose",
    components: {
         ToRecipientsContainer
    },
    data: function () {
        return {
            email : {
                user_id : null,
                subject : '',
                body_html : '',
                body_text : '',
                files : [],
                recipients : []
            }
        }
    },
    mounted(){

    },
    props: [
        'user',
    ],
    computed : {
        hasRecipients : function(){
            return (this.email.recipients.length > 0);
        },
        getFromName : function (){
            return (this.user) ? this.user.name : "-";
        }
    },
    methods : {

        sendEmail : function(){
            let pushEmail = this.email;
            this.$emit('send-email', pushEmail);
            this.cancelCompose(); //now we can close it
        },
        removeRecipient : function(email){
            let indexOfCurrentRecipient = this._indexOfExistingEmail(email)
            if(indexOfCurrentRecipient !== -1){
                this.email.recipients.splice(indexOfCurrentRecipient, 1);
            }
        },
        addRecipient : function(recipient){
            let email = recipient.email;
            let rindex = this._indexOfExistingEmail(email);
            if(rindex === -1){
                this.email.recipients.push(recipient);
            }
        },
        pushFile : function(){
            this.email.files = this.$refs.files.files;
        },
        cancelCompose : function(){
            this.email.subject = "";
            this.email.body_html = "";
            this.email.body_text = "";
            this.email.attachments = [];
            this.email.recipients = [];
            this.$emit('cancel-compose');
        },
        _indexOfExistingEmail : function(email){
            return this.email.recipients.map(function(r) { return r.email; }).indexOf(email);
        }
    }
};
</script>

<style>

</style>
