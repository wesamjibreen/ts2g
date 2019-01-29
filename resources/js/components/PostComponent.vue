<template>
    <div>
    </div>
</template>

<script>
    var _this = {};
    export default {
        props: ['data'],
        name: "post",
        data() {
            return {
                post: {},
                comment_text: ''
            }
        },
        created() {
            _this = this;
            _this.post = _this.data.post;
            _this.auth_user = _this.data.auth_user;
            // console.log(_this.post);
        },
        mounted() {
        },
        methods: {
        }
    }

</script>

<style scoped>

</style>