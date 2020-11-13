<template>
    <nav aria-label="Emails">
        <ul class="pagination">
            <li :class="getPageActiveClass(p)" :key="p" v-for="p in pages">
                <a  class="page-link" @click="goToPage(p)" href="#">{{p}}</a>
            </li>
        </ul>
    </nav>
</template>


<script>

export default {
    name : "PaginationContainer",
    components: {

    },
    data: function () {
        return {
        }
    },
    mounted(){

    },
    props: [
        'pages',
        'page'
    ],
    computed : {

    },
    methods : {
        getPageActiveClass : function(p){
            return (p === this.page) ? 'page-item active' : 'page-item';
        },
        goToPage : function(page){
            this.$emit('goto-page', page);
        }

    }
};
</script>

<style>

</style>
