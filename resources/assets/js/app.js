/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import VueRouter from 'vue-router';
import VueBootstrapToasts from "vue-bootstrap-toasts";
require('./bootstrap');
window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(VueBootstrapToasts);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/** Pages */
/** Login component */
let oLoginComponent = Vue.component('login', require('./components/pages/LoginComponent.vue').default);
/** Admin component */
let oAdminComponent = Vue.component('admin', require('./components/pages/AdminComponent.vue').default);

/** Admin Modules*/
/** Company component */
let oAdminCompanyComponent = Vue.component('company', require('./components/modules/admin/AdminCompanyComponent.vue').default);
/** Employee component */
let oAdminEmployeeComponent = Vue.component('employee', require('./components/modules/admin/AdminEmployeeComponent.vue').default);

/** AdminLTE Modules */
/** Navbar */
Vue.component('admin-lte-navbar', require('./components/modules/AdminLTE/AdminLteNavbarComponent.vue').default);
/** Sidebar */
Vue.component('admin-lte-sidebar', require('./components/modules/AdminLTE/AdminLteSidebarComponent.vue').default);
/** Footer */
Vue.component('admin-lte-footer', require('./components/modules/AdminLTE/AdminLteFooterComponent.vue').default);
/** Content Header */
Vue.component('admin-lte-content-header', require('./components/modules/AdminLTE/content/AdminLteContentHeaderComponent.vue').default);

/** Modal */
Vue.component('ClipLoader', require('./components/modals/ClipLoader.vue').default);

/** Admin route components */
let oAdminRoutes = [
    {
        path      : '/company',
        name      : 'company',
        component : oAdminCompanyComponent
    },
    {
        path      : '/employee',
        name      : 'employee',
        component : oAdminEmployeeComponent
    }
];

/** Sub route components */
let oSubRoutes = [
    {
        path      : '/',
        name      : 'login',
        component : oLoginComponent
    },
    {
        path      : '/admin',
        name      : 'admin',
        component : oAdminComponent,
        children  : oAdminRoutes
    }
];

/** route components **/
let oRouter = new VueRouter({
    routes : [{
        path      : '/',
        name      : 'main',
        component : Vue.component('main_app', require('./components/MainComponent.vue').default),
        children  : oSubRoutes
    }]
});

const app = new Vue({
    el: '#app',

    router : oRouter,

    data : {
        oLoading : {
            bTable : false
        }
    },

    methods : {
        /**
         * Activate loading table
         */
        activateLoadingTable : function() {
            this.oLoading.bTable = true;
        },

        /**
         * Deactivate loading table
         */
        deactivateLoadingTable : function() {
            this.oLoading.bTable = false;
        },

        /**
         * Axios GET request
         *
         * @param sUrl
         * @param mThen
         * @param oParam
         * @returns {Promise<unknown>}
         */
        getRequest : function (sUrl, mThen, oParam = []) {
            return new Promise((mResolve) => {
                axios({
                    method : 'get',
                    url    : sUrl,
                    params : oParam
                })
                    .then(function (oResponse) {
                        if (oResponse.data.code === 200) {
                            mThen(oResponse.data.data);
                        } else {
                            this.catchResponse(oResponse);
                        }
                    })
                    .catch(this.catchRequest);
                mResolve();
            })
        },

        /**
         * Axios POST request
         *
         * @param sUrl   string
         * @param oParam object
         * @param mThen  mixed
         * @return {Promise<any>}
         */
        postRequest: function (sUrl, oParam, mThen) {
            let oHeaders = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            };

            return new Promise((mResolve) => {
                axios.post(sUrl, oParam, {headers: oHeaders})
                    .then((oResponse) => {
                        if (oResponse.data.code === 200) {
                            mThen(oResponse.data.data);
                        } else {
                            this.catchResponse(oResponse);
                        }
                    })
                    .catch(this.catchRequest);
                mResolve();
            });
        },

        /**
         * Axios Delete request
         *
         * @param sUrl
         * @param mThen
         * @returns {Promise<unknown>}
         */
        deleteRequest : function (sUrl, mThen) {
            return new Promise((mResolve) => {
                axios({
                    method : 'delete',
                    url    : sUrl
                })
                    .then(function (oResponse) {
                        if (oResponse.data.code === 200) {
                            mThen(oResponse.data.data);
                        } else {
                            this.catchResponse(oResponse);
                        }
                    })
                    .catch(this.catchRequest);
                mResolve();
            })
        },

        /**
         * Axios Put request
         *
         * @param sUrl
         * @param mThen
         * @param oParam
         * @returns {Promise<unknown>}
         */
        putRequest : function (sUrl, oParam, mThen) {
            return new Promise((mResolve) => {
                axios({
                    method : 'put',
                    url    : sUrl,
                    params : oParam
                })
                    .then(function (oResponse) {
                        if (oResponse.data.code === 200) {
                            mThen(oResponse.data.data);
                        } else {
                            this.catchResponse(oResponse);
                        }
                    })
                    .catch(this.catchRequest);
                mResolve();
            })
        },

        /**
         * Axios patch request
         *
         * @param sUrl
         * @param mThen
         * @param oParam
         * @returns {Promise<unknown>}
         */
        patchRequest : function (sUrl, oParam, mThen) {
            return new Promise((mResolve) => {
                axios({
                    method : 'patch',
                    url    : sUrl,
                    params : oParam
                })
                    .then(function (oResponse) {
                        if (oResponse.data.code === 200) {
                            mThen(oResponse.data.data);
                        } else {
                            this.catchResponse(oResponse);
                        }
                    })
                    .catch(this.catchRequest);
                mResolve();
            })
        },

        /**
         * Catch axios response
         *
         * @param oResponse
         */
        catchResponse : function(oResponse) {
            this.$toast.error(oResponse.message);
        },

        /**
         * Catch axios request error
         */
        catchRequest : function(oError) {
            this.$toast.error(oError.message);
        },

    }
});
