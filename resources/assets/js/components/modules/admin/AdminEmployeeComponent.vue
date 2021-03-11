<template>
    <div class="content-wrapper">
        <admin-lte-content-header title="Employee"></admin-lte-content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <button v-on:click="addEmployee()">Add</button>
                                <div v-if="bShow === true">
                                    <form>
                                        <input type="text" placeholder="First Name" v-model="oForm.first_name">
                                        <input type="text" placeholder="Last Name" v-model="oForm.last_name">
                                        <select v-model="oForm.company_id">
                                            <option disabled selected value="null">Please select company</option>
                                            <option v-for="oCompanyDetails in aCompanies" :value="oCompanyDetails.company_id">{{ oCompanyDetails.name }}</option>
                                        </select>
                                        <input type="text" placeholder="Email" v-model="oForm.email">
                                        <input type="tel" placeholder="Phone" v-model="oForm.phone">
                                        <div>
                                            <button v-on:click="submitEmployeeInput()">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="employee-table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Company Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="iEmployeesCount === 0 && $root.oLoading.bTable === false">
                                        <td class="no-data" colspan="6">No Registered Employee</td>
                                    </tr>
                                    <tr v-if="$root.oLoading.bTable === true">
                                        <td class="no-data" colspan="6"><ClipLoader></ClipLoader></td>
                                    </tr>
                                    <tr v-else v-for="oEmployeeDetail in aEmployees">
                                        <td>{{ oEmployeeDetail.first_name }}</td>
                                        <td>{{ oEmployeeDetail.last_name }}</td>
                                        <td>{{ oEmployeeDetail.t_companies.name }}</td>
                                        <td>{{ oEmployeeDetail.email }}</td>
                                        <td>{{ oEmployeeDetail.phone }}</td>
                                        <td>
                                            <button v-on:click="updateEmployee(oEmployeeDetail)"><i class="fas fa-edit"></i></button>
                                            <button v-on:click="deleteEmployee(oEmployeeDetail.employee_id)"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="6">
                                            <ul class="pagination">
                                                <li v-for="iIndex in oPagination.aPages" v-on:click="changePage(iIndex)">{{ iIndex }}</li>
                                            </ul>
                                        </td>
                                    </tfoot>
                                </table>
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
        name: 'AdminEmployeeComponent',
        data() {
            return {
                aEmployees      : {},
                aCompanies      : {},
                iEmployeesCount : 0,
                bShow           : false,
                oForm           : {
                    first_name : null,
                    last_name  : null,
                    company_id : null,
                    email      : null,
                    phone      : null,
                },
                sAction        : null,
                oPagination     : {
                    aPages : [],
                    iOffset : 0,
                    iLimit : 10,
                    iPage : 1,
                }
            };
        },
        created() {
            this.getEmployees();
        },
        methods : {
            /**
             * Setup pagination of the table
             */
            setupPagination : function() {
                let iTotalPage = (this.iEmployeesCount + this.oPagination.iLimit - 1) / this.oPagination.iLimit;
                for(let iIndex = 1; iIndex <= iTotalPage; iIndex++) {
                    this.oPagination.aPages.push(iIndex);
                }
            },

            /**
             * Get all employees
             */
            getEmployees : function () {
                let sUrl = '/employees';
                this.$root.activateLoadingTable();
                this.$root.getRequest(sUrl, (mResponse) => {
                    let oData = mResponse.data;
                    if (mResponse.code === 200) {
                        this.iEmployeesCount = oData.count;
                        this.aEmployees = oData.employees;
                        this.setupPagination();
                        this.$root.deactivateLoadingTable();
                    }
                }, {'offset' : this.oPagination.iOffset});
            },

            /**
             * Get all companies
             */
            getCompanies : function () {
                let sUrl = '/companies';
                this.$root.getRequest(sUrl, (mResponse) => {
                    let oData = mResponse.data;
                    if (mResponse.code === 200) {
                        this.aCompanies = oData.companies;
                    }
                });
            },

            /**
             * Delete employee
             *
             * @param iEmployeeId
             */
            deleteEmployee : function (iEmployeeId) {
                let sUrl = '/employees/' + iEmployeeId;
                this.$root.activateLoadingTable();
                this.$root.deleteRequest(sUrl, (mResponse) => {
                    if (mResponse.code === 200) {
                        this.$toast.success(mResponse.data.message);
                        this.getEmployees();
                    }
                });
            },

            /**
             * Add employee
             */
            addEmployee : function () {
                this.getCompanies();
                this.sAction = 'create';
                this.bShow = true;
            },

            /**
             * Update employee
             */
            updateEmployee : function (oEmployeeDetail) {
                this.getCompanies();
                this.sAction = 'update';
                this.oForm = {
                    employee_id : oEmployeeDetail.employee_id,
                    first_name  : oEmployeeDetail.first_name,
                    last_name   : oEmployeeDetail.last_name,
                    company_id  : oEmployeeDetail.company_id,
                    email       : oEmployeeDetail.email,
                    phone       : oEmployeeDetail.phone
                };
                this.bShow = true;
            },

            /**
             * Submit employee input
             *
             * @param iEmployeeId
             */
            submitEmployeeInput : function () {
                switch (this.sAction) {
                    case 'create':
                        this.storeEmployee();
                        break;
                    case 'update':
                        this.putEmployee();
                        break;
                }
            },

            /**
             * Empty form
             */
            emptyForm : function () {
                this.oForm = {
                    first_name : null,
                    last_name  : null,
                    company_id : null,
                    email      : null,
                    phone      : null
                };
            },

            /**
             * Store employee
             */
            storeEmployee : function () {
                let sUrl = '/employees';
                this.$root.activateLoadingTable();
                this.$root.postRequest(sUrl, this.oForm, (mResponse) => {
                    if (mResponse.data.status === false) {
                        this.$root.deactivateLoadingTable();
                        return this.$toast.error(mResponse.data.message);
                    }

                    this.$toast.success('Successfully created ' + this.oForm.first_name + ' ' + this.oForm.last_name);
                    this.emptyForm();
                    this.getEmployees();
                    this.bShow = false;
                });
            },

            /**
             * Put employee
             */
            putEmployee : function () {
                let sUrl = '/employees/' + this.oForm.employee_id;
                this.$root.activateLoadingTable();
                this.$root.putRequest(sUrl, this.oForm, (mResponse) => {
                    if (mResponse.data.status === false) {
                        this.$root.deactivateLoadingTable();
                        return this.$toast.error(mResponse.data.message);
                    }

                    this.$toast.success('Successfully updated employee details of ' + this.oForm.first_name + ' ' + this.oForm.last_name);
                    this.emptyForm();
                    this.getEmployees();
                    this.bShow = false;
                });
            },

            /**
             * Change page
             *
             * @param iPage
             */
            changePage : function (iPage) {
                this.oPagination.iOffset = (this.oPagination.iLimit * iPage) - this.oPagination.iLimit;
                this.oPagination.aPages = [];
                this.getEmployees();
            }
        }
    }
</script>

<style scoped>
    th,
    td {
        text-align: center;
    }

    .no-data {
        text-align: center;
    }

    .card-header {
        text-align: end;
    }

    .pagination {
        float: right;
    }

    .pagination li {
        margin-right: 1em;
    }

    .pagination li,
    button  {
        background-image: linear-gradient(#FFAF00, #FF9800);
        border: none;
        color: white;
        padding: 1px 8px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        text-transform: uppercase;
        border-radius: 5px 5px 5px 5px;
    }



    input[type=text],
    input[type=tel],
    select {
        background-color: #f6f6f6;
        border: none;
        color: #0d0d0d;
        padding: 15px 32px;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 5px;
        width: 85%;
        border: 2px solid #f6f6f6;
        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 5px 5px 5px 5px;
    }

    input[type=text]:focus,
    input[type=tel]:focus,
    select:focus {
        background-color: #fff;
        border-bottom: 2px solid #5fbae9;
    }

    input[type=text]:placeholder,
    input[type=tel]:placeholder,
    select:placeholder {
        color: #cccccc;
    }

    select {
        right: auto;
        left: 50%;
    }

    form {
        margin: 1em 0em;
        padding: 1em 0em;
        border: 2px solid lightgrey;
        border-radius: 5px;
        text-align: center;
    }

    form button {
        background-image: linear-gradient(#FFAF00, #FF9800);
        border: none;
        color: white;
        padding: 15px 80px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        text-transform: uppercase;
        font-size: 13px;
        -webkit-border-radius: 5px 5px 5px 5px;
        border-radius: 5px 5px 5px 5px;
    }
</style>
