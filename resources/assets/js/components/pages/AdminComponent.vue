<template>
    <div>
        <div class="wrapper">
            <admin-lte-navbar></admin-lte-navbar>
            <admin-lte-sidebar></admin-lte-sidebar>
            <router-view></router-view>
            <admin-lte-footer></admin-lte-footer>
        </div>
    </div>
</template>

<script>
    export default {
        name : 'AdminComponent'
    }
</script>

<style scoped>

</style>
