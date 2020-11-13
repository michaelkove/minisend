<template>
    <div id="emailPreview">
        <div class="card border-1">
            <div class="card-header">
                <button type="button" class="btn btn-outline-primary float-right" @click="cancelPreview">Close (X)</button>
                <h5 class="card-title" v-html="getEmailSubject"></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Recipient</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="recipient in email.recipients" :key="recipient.id">
                                    <td>
                                        <span v-html="recipient.email"></span>
                                    </td>
                                    <td>
                                        <span :class="getStatusClass(recipient.pivot.status)">{{recipient.pivot.status}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <h6>HTML Body</h6>
                                <div v-html="getEmailHtml"></div>
                            </div>
                            <div class="col-12">
                                <h6>Text Body</h6>
                                <div v-html="getEmailText"></div>
                            </div>
                            <div class="col-12">
                                <h6>Attachments</h6>
                                <span class="badge badge-pill" v-for="attachment in email.attachments" :key="attachment.id">{{attachment.name}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>

export default {
    name : "EmailPreview",
    components: {

    },
    data: function () {
        return {

        }
    },
    mounted(){

    },
    props: [
        'email'
    ],
    computed : {
        getEmailSubject : function(){
            return this.email.subject;
        },
        getEmailHtml : function(){
            return this.email.body_html;
        },
        getEmailText : function(){
            return this.email.body_text;
        }
    },
    methods : {
        cancelPreview : function(){
            this.$emit('cancel-preview');
        },
        getStatusClass : function(status){
            let statusClass = 'badge badge-pill ';
            if(status == 'posted'){
                statusClass = statusClass + " badge-primary";
            } else if(status === 'sent'){
                statusClass = statusClass + " badge-success";
            } else {
                statusClass = statusClass + " badge-danger";
            }
            return statusClass;
        }
    }
};
</script>

<style>

</style>
