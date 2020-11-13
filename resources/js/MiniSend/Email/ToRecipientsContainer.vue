<template>
    <div id="recipientsContainer">
        <div id="recipientsToSendTo">
            <div class="col-12 p-2">
                <div class="d-inline-block" v-for="recipient in recipients" :key="recipient.email">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">{{recipient.email}}</span>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-danger btn-outline-secondary" @click="removeRecipient(recipient.email)" type="button" id="button-addon2">X</button>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <div id="addNewRecipientContainer">
            <div class="form-group">
                <div class="col-12">
                    <div class="input-group">
                        <input type="email" class="form-control" v-model="emailToAdd" @keyup.enter="addRecipient()">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="addRecipient">+ ADD</button>
                        </div>
                    </div>
                    <div class="block-help"><em><small v-html="getHelpText"></small></em></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name : "ToRecipientsContainer",
    components: {

    },
    data: function () {
        return {
            emailToAdd : null,
            emailInvalid : false
        }
    },
    mounted(){

    },
    props: [
        'recipients',
    ],
    computed : {
        getHelpText : function(){
            if(this.emailInvalid){
                return "<span class='text-danger'>Invalid Email!</span>";
            }
            return "Type email address and hit Enter to add"
        }
    },
    methods : {
        removeRecipient : function(email){
            this.$emit('remove-recipient', email);
        },
        addRecipient : function(){
            if(this.emailToAdd){
                let email = this.emailToAdd.toLowerCase().trim();
                if(this._validateEmail(email)){
                    this.emailInvalid = false;
                    let recipient = {email : email};
                    this.$emit('add-recipient', recipient);
                    this.emailToAdd="";
                } else {
                    this.emailInvalid = true;
                }
            }
        },
        _validateEmail : function ValidateEmail(email)
        {
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email))
            {
                return (true)
            }
            return (false)
        }
    }
};
</script>

<style>

</style>
