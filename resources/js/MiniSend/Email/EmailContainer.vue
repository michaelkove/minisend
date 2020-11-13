<template>
    <div id="emailContainer">
        <div v-if="compose">
            <email-compose
                :user.sync="user"
                @cancel-compose="cancelCompose"
                @send-email="sendEmail"
            ></email-compose>
        </div>
        <div v-else>

            <div class="row">
                <div class="col-12" v-if="isPreview">
                    <email-preview :email.sync="previewEmail" @cancel-preview="cancelPreview"></email-preview>
                </div>
                <div class="col" v-else>
                    <div class="row">
                        <div class="col-12">
                            <div class="row pb-2">
                                <div class="col-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" v-model="search.q" aria-label="Search emails ..." @keyup.enter="searchEmails()">

                                        <select class="custom-select" id="inputSearchGroup" aria-label="Search Emails" v-model="search.location">
                                            <option value="all">Everywhere</option>
                                            <option value="subject">Subject</option>
                                            <option value="sender">Sender</option>
                                            <option value="recipient">Recipient</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" @click="searchEmails">Search</button>
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger" type="button" @click="cancelSearch">Clear</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col text-right">
                                    <button type="button" class="btn btn-outline-primary btn-block" @click="openCompose">
                                        Compose New Email
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <pagination-container :page.sync="page" :pages.sync="pages" @goto-page="goToPage"></pagination-container>
                        </div>
                    </div>

                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th colspan="5" class="text-center">Stats</th>
                        </tr>
                        <tr>
                            <th colspan="3">Email</th>
                            <th class="text-center">Files</th>
                            <th class="text-center">Posted</th>
                            <th class="text-center">Sent</th>
                            <th class="text-center">Failed</th>
                            <th class="text-center">Total</th>
                        </tr>
                        </thead>
                        <tbody v-if="hasEmails">
                        <email v-for="email in emails" :email.sync="email" :key="email.id" @open-preview="openPreview"></email>

                        </tbody>
                        <tbody v-else>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="alert alert-info">No Emails To Display</div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</template>
<script>

import Email from './Email';
import EmailCompose from "./EmailCompose";
import EmailPreview from "./EmailPreview";
import PaginationContainer from "./PaginationContainer";

export default {
    name : "EmailContainer",
    components: {
        PaginationContainer,
        EmailPreview,
        EmailCompose,
        Email
    },
    data: function () {
        return {
            compose : false,
            preview : false,
            previewEmail : null,
            search : {
                q : null,
                location : 'all'
            }
        }
    },
    mounted(){

    },
    props: [
        'page',
        'pages',
        'emails',
        'user'
    ],
    computed : {
        hasEmails : function(){
            return (this.emails && this.emails.length > 0);
        },
        isPreview : function(){
            return this.preview;
        }
    },
    methods : {
        goToPage : function(page){
            let search = (this.search.q !== null) ? this.search : null;
            this.$emit('goto-page',page, search);
        },
        sendEmail : function(email){
            this.$emit('send-email', email);
        },
        cancelCompose : function(){
            this.compose = false;
        },
        openCompose : function(){
            this.compose = true;
        },
        openPreview : function(email){
            this.previewEmail = email;
            this.preview = true;
        },
        cancelPreview : function(){
            this.preview = false;
            this.previewEmail = null;
        },
        searchEmails: function(){
            this.$emit('search-emails', this.search);
        },
        cancelSearch: function(){
            this.search.q = null;
            this.$emit('cancel-search');
        }
    }
};
</script>

<style scoped>
    .previewPanel {
        position: fixed;
        z-index: 9999;
    }
</style>
