<template>
    <div class="row">
        <div class="col-md-12">
            <div v-if="loading">
                <div class="col-6 mx-auto">
                    <div class="alert alert-info">Loading ...</div>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-12">
                    <div class="col-5 pb-3">
                        <div v-show="isInProcess" class="col-12">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :aria-valuenow="isInProcess" aria-valuemin="0" aria-valuemax="100" :style="getProcessStyle"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div v-if="hasMessages" class="col-12">
                            <button class="btn btn-link float-right" @click="clearMessages">Cose All X</button>
                            <div class="clearfix">
                                <div class="alert alert-info" v-for="message in messages">
                                    <h5 v-html="message.message"></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12">
                    <EmailContainer
                        :user.sync="user"
                        :emails.sync="getEmailsOnPage"
                        :page.sync="this.currentPage"
                        :pages.sync="this.pages"
                        @send-email="sendEmail"
                        @search-emails="searchEmails"
                        @cancel-search="getUserEmails"
                        @goto-page="goToPage"
                    ></EmailContainer>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import axios from "axios";
import EmailContainer from "./Email/EmailContainer";

export default {
    name : "MiniSendApp",
    components: {
        EmailContainer
    },
    data: function () {
        return {
            processing : false,
            loading : true,
            emails : {},
            user : null,
            pages : 1,
            currentPage : 1,
            messages : []
        }
    },
    mounted () {
        this.getUser(this.userid);
        // if(this.user){
        //
        // }


        if(this.user){
            /*let that = this;
            Echo.private('user.'+this.user.id)
                .listen('.sent.update', (email) => {
                    //TODO: push new email onto stack
                    // find email by id
                });*/
        }

    },
    props: [
        'userid'
    ],
    computed : {
        getEmailsOnPage : function(){
            return (this.emails && this.emails.length) ? this.emails : [];
        },
        hasMessages : function(){
            return (this.messages.length > 0);
        },
        isInProcess : function(){
            return this.processing;
        },
        getProcessStyle : function(){
            return "width: "+this.processing+"%";
        },

    },
    methods: {
        sendEmail : function(emailData){
            this.processing = 25;
            let userId = this.user.id;

            let formData = new FormData();

            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('subject', emailData.subject);
            formData.append('body_text', emailData.body_text);
            formData.append('body_html', emailData.body_html);
            formData.append('_method', 'POST');

            //ghetto way to attach array to this shenannigans
            if(emailData.files){
                for( var i = 0; i < emailData.files.length; i++ ){
                    let file = emailData.files[i];
                    formData.append('attachments[' + i + ']', file);
                }
            }

            if(emailData.recipients){
                for( var i = 0; i < emailData.recipients.length; i++ ){
                    let to = emailData.recipients[i];
                    formData.append('recipients[' + i + '][email]', to.email);
                }
            }


            //format data properly for API's consumption
            let data = {
                _token : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                _method : "POST",
                subject : emailData.subject,
                body_text : emailData.body_text,
                body_html : emailData.body_html,
                recipients : emailData.recipients,
                attachments : emailData.files,
            }
            axios
                .post('http://localhost/api/user/'+userId+"/email", formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                .then((response) => {
                    this.processing = 100;
                    this._processEmailResponse(response);
                })
                .catch(error => console.log(error)).finally(() => this.processing = false);
        },
        getUser : function(userId){
            axios
                .get('http://localhost/api/user/'+userId)
                .then((response) => {
                    this.user = response.data;
                    this.getUserEmails();
                })
                .catch(error => console.log(error));
        },
        getUserEmails : function(page = null){
            this.processing = 15;
            let userId = this.user.id;
            let currentPage = (page) ? page : this.currentPage;
            axios
                .get('http://localhost/api/user/'+userId+'/email?page='+currentPage)
                .then((response) => {
                    this.processing = 85;
                    this._processEmailResponse(response);
                })
                .catch(error => console.log(error))
                .finally(() => {
                    this.loading = false;
                    this.processing = false;
                })
        },
        searchEmails : function(search, page = null){
            this.processing = 33;
            let encodedQuery = encodeURI(search.q);
            let encodedLocation = encodeURI(search.location);
            let userId = this.user.id;
            let currentPage = (page) ? page : this.currentPage;
            axios
                .get('http://localhost/api/user/'+userId+'/email/search?q='+encodedQuery+'&location='+encodedLocation+'&page='+currentPage)
                .then((response) => {
                    this.processing = 85; //arbitrary percent..just for fun of it. so it doesn't look like app is stuck. Obv. in prod should be a better implement.
                    this._processEmailResponse(response);
                    this.processing = 100;
                })
                .catch(error => console.log(error))
                .finally(() => {
                    this.loading = false;
                    this.processing =false;
                })
        },
        goToPage : function(page, search){
            if(search){
                this.searchEmails(search);
            } else {
                this.getUserEmails()
            }
        },
        _processEmailResponse : function(responseData){
            let data = responseData.data;
            if(data.emails && data.emails.data){
                this.emails = data.emails.data;
                if(data.emails.current_page){
                    this.currentPage = data.emails.current_page;
                }
                if(data.emails.last_page){
                    this.pages = data.emails.last_page;
                }
            }
            if(data.messages && data.messages.length){
                this.messages = data.messages;
            }

            // this.pages = 1;
        },
        clearMessages : function(){
            this.messages = [];
        }

    }
};
</script>

<style>
.brand-link{
    color:#007bff;
    cursor: pointer;
}

</style>
