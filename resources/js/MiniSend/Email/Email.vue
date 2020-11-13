<template>
    <tr class="pointer-event">
        <td>
            <span v-html="getFrom"></span>
        </td>
        <td>
            <span @click="openPreview()" class="brand-link" v-html="getSubject"></span>
        </td>
        <td class="text-center">
            <small v-html="getSentDate"></small>
        </td>
        <td class="text-center">
            <small v-html="getAttachmentCount"></small>
        </td>
        <td class="text-center">
            <span class="text-info" v-html="getPostedCount"></span>
        </td>
        <td class="text-center">
            <span class="text-success" v-html="getSentCount"></span>
        </td>
        <td class="text-center">
            <span class="text-danger" v-html="getFailedCount"></span>
        </td>
        <td class="text-center">
            <span class="" v-html="getTotalCount"></span>
        </td>
    </tr>
</template>

<script>

export default {
    name : "Email",
    components: {

    },
    data: function () {
        return {

        }
    },
    mounted(){

    },
    props: [
        'email',
    ],
    computed : {
        getFrom : function(){
            return (this.email && this.email.user) ? this.email.user.name : "You";
        },
        getToText : function(){
            return  this.getToCount + (this.getToCount > 1) ? " Recipients" : " Recipient";
        },
        getToCount : function(){
            let toCount = (this.email && this.email.recipients) ? this.email.recipients.length : 0;
            return (toCount > 0) ? toCount : "-";
        },
        getSubject : function(){
            let curSubj = (this.email && this.email.subject) ? this.email.subject.trim() : null;

            return (curSubj && curSubj != "") ? this.email.subject : "<em>(no subject)</em>";
        },
        getSentDate : function(){
            let createdAt = new Date(this.email.created_at);
            if(createdAt){
                return createdAt.toDateString()+" "+createdAt.toTimeString();
            }
            // return (this.email) ? this.email.created_at : "";
        },
        getSentCount : function(){
            if(this.email.recipients){
                let count = this.email.recipients.filter(function(recipient){
                    return (recipient.pivot.status === 'sent');
                });
                return count.length;
            }
            return "N/A";
        },
        getPostedCount : function(){
            if(this.email.recipients){
                let count = this.email.recipients.filter(function(recipient){
                    return (recipient.pivot.status === 'posted');
                });
                return count.length;
            }
            return "N/A";
        },
        getFailedCount : function(){
            if(this.email.recipients){
               let count = this.email.recipients.filter(function(recipient){
                    return (recipient.pivot.status === 'failed');
                });
                return count.length;
            }
            return "N/A";
        },
        getTotalCount : function(){
            return this.email.recipients.length;
        },
        getAttachmentCount : function(){
            return this.email.attachments.length;
        },

    },
    methods : {
        openPreview : function(){
            this.$emit('open-preview', this.email);
        }
    }
};
</script>

<style>

</style>
