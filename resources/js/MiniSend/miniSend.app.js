import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import axios from 'axios'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import MiniSendApp from './MiniSendApp';

//no need to start mini app if element isn't loaded
if(document.getElementById('miniSendApp'))
{
    const app = new Vue({
        el: '#miniSendApp',
        components: {
            MiniSendApp
        },
        data : function(){
            return {

            }
        },


    });

}
