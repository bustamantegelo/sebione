<template>
    <div>
        <Toasts
            :show-progress="false"
            :rtl="false"
            :max-messages="5"
            :time-out="2000"
            :closeable="false"
        ></Toasts>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name : 'MainComponent'
    }
</script>

<style scoped>

</style>
