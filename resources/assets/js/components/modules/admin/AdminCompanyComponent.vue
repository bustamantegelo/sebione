<template>
    <div class="content-wrapper">
        <admin-lte-content-header title="Company"></admin-lte-content-header>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <button v-on:click="addCompany()">Add</button>
                                <div v-if="bShow === true">
                                    <form>
                                        <input type="text" placeholder="Name" v-model="oForm.name">
                                        <input type="text" placeholder="Email" v-model="oForm.email">
                                        <input type="text" placeholder="Website" v-model="oForm.website">
                                        <div class="uploadBox">
                                            <div v-if="!oForm.logo">
                                                <p> Click to browse</p>
                                                <input class="input-file" type="file" accept="image/*" @change="onFileLogoChange($event.target.files)">
                                            </div>
                                            <div v-else>
                                                <img v-if="(sAction === 'create') || ((sAction === 'update') && (bImageChanged === true))" :src="oForm.logo" />
                                                <img v-else-if="(sAction === 'update') && (bImageChanged === false)" v-bind:src="'storage/img//' + oForm.logo" />
                                                <button class="remove-image" @click="removeImage">Remove image</button>
                                            </div>
                                        </div>
                                        <div>
                                            <button v-on:click="submitCompanyInput()">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="company-table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Logo</th>
                                        <th>Webiste</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="iCompaniesCount === 0 && $root.oLoading.bTable === false">
                                            <td class="no-data" colspan="5">No Registered Company</td>
                                        </tr>
                                        <tr v-if="$root.oLoading.bTable === true">
                                            <td class="no-data" colspan="5"><ClipLoader></ClipLoader></td>
                                        </tr>
                                        <tr v-else v-for="oCompanyDetail in aCompanies">
                                            <td>{{ oCompanyDetail.name }}</td>
                                            <td>{{ oCompanyDetail.email }}</td>
                                            <td v-if="oCompanyDetail.logo === null">{{ oCompanyDetail.logo }}</td>
                                            <td v-else><img v-bind:src="'storage/img//' + oCompanyDetail.logo"></td>
                                            <td>{{ oCompanyDetail.website }}</td>
                                            <td>
                                                <button v-on:click="updateCompany(oCompanyDetail)"><i class="fas fa-edit"></i></button>
                                                <button v-on:click="deleteCompany(oCompanyDetail.company_id)"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="5">
                                            <ul class="pagination">
                                                <li v-for="iIndex in oPagination.aPages" v-on:click="changePage(iIndex)">{{ iIndex }}</li>
                                            </ul>
                                        </td>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: 'AdminCompanyComponent',
        data() {
            return {
                aCompanies      : {},
                iCompaniesCount : 0,
                bShow           : false,
                oForm           : {
                    name    : null,
                    email   : null,
                    logo    : '',
                    website : null

                },
                sAction         : null,
                bImageChanged   : false,
                oPagination     : {
                    aPages : [],
                    iOffset : 0,
                    iLimit : 10,
                    iPage : 1,
                }
            };
        },
        created() {
            this.getCompanies();
        },
        methods : {
            /**
             * Setup pagination of the table
             */
            setupPagination : function() {
                let iTotalPage = (this.iCompaniesCount + this.oPagination.iLimit - 1) / this.oPagination.iLimit;
                for(let iIndex = 1; iIndex <= iTotalPage; iIndex++) {
                    this.oPagination.aPages.push(iIndex);
                }
            },

            /**
             * Get all companies
             */
            getCompanies : function () {
                let sUrl = '/companies';
                this.oPagination.aPages = [];
                this.$root.activateLoadingTable();
                this.$root.getRequest(sUrl, (mResponse) => {
                    let oData = mResponse.data;
                    if (mResponse.code === 200) {
                        this.iCompaniesCount = oData.count;
                        this.aCompanies = oData.companies;
                        this.setupPagination();
                        this.$root.deactivateLoadingTable();
                    }
                }, {'offset' : this.oPagination.iOffset});
            },

            /**
             * Delete company
             *
             * @param iCompanyId
             */
            deleteCompany : function (iCompanyId) {
                let sUrl = '/companies/' + iCompanyId;
                this.$root.activateLoadingTable();
                this.$root.deleteRequest(sUrl, (mResponse) => {
                    if (mResponse.code === 200) {
                        this.$toast.success(mResponse.data.message);
                        this.getCompanies();
                    }
                });
            },

            /**
             * Add company
             */
            addCompany : function () {
                this.sAction = 'create';
                this.bShow = true;
            },

            /**
             * Update company
             *
             * @param oCompanyDetail
             */
            updateCompany : function (oCompanyDetail) {
                this.sAction = 'update';
                this.oForm = {
                    company_id : oCompanyDetail.company_id,
                    name       : oCompanyDetail.name,
                    email      : oCompanyDetail.email,
                    logo       : oCompanyDetail.logo,
                    website    : oCompanyDetail.website
                };
                this.bShow = true;
            },

            /**
             * Submit company input
             */
            submitCompanyInput : function () {
                switch (this.sAction) {
                    case 'create':
                        this.storeCompany();
                        break;
                    case 'update':
                        this.putCompany();
                        break;
                }
            },

            /**
             * Empty form value
             */
            emptyForm : function () {
                this.oForm = {
                    name    : null,
                    email   : null,
                    logo    : null,
                    website : null
                };
            },

            /**
             * Store company input
             */
            storeCompany : function () {
                let sUrl = '/companies';
                this.$root.activateLoadingTable();
                this.$root.postRequest(sUrl, this.oForm, (mResponse) => {
                    if (mResponse.data.status === false) {
                        this.$root.deactivateLoadingTable();
                        return this.$toast.error(mResponse.data.message);
                    }

                    this.$toast.success('Successfully created ' + this.oForm.name);
                    this.emptyForm();
                    this.getCompanies();
                    this.bShow = false;
                });
            },

            /**
             * Put company input
             */
            putCompany : function () {
                let sUrl = '/companies/' + this.oForm.company_id;
                this.$root.activateLoadingTable();
                this.$root.postRequest(sUrl, this.oForm, (mResponse) => {
                    if (mResponse.data.status === false) {
                        this.$root.deactivateLoadingTable();
                        return this.$toast.error(mResponse.data.message);
                    }

                    this.$toast.success('Successfully updated company details of ' + this.oForm.name);
                    this.emptyForm();
                    this.getCompanies();
                    this.bShow = false;
                    this.bImageChanged = false;
                });
            },

            /**
             * On file logo change
             *
             * @param oFileList
             */
            onFileLogoChange : function (oFileList) {
                if (oFileList.length === 1) {
                    if (this.sAction === 'update') {
                        this.bImageChanged = true;
                    }
                    this.createImage(oFileList[0]);
                }
            },

            /**
             * Create image
             *
             * @param oFile
             */
            createImage : function (oFile) {
                let oReader = new FileReader();

                oReader.onload = (oEvent) => {
                    let oImageFile = oEvent.target.result;
                    this.validateDimension(oImageFile, (mRightDimension) => {
                        if (mRightDimension !== true) {
                            return this.oForm.logo = mRightDimension;
                        }

                        this.$toast.error('Image file is less than 100x100 dimension');
                    });
                };
                oReader.readAsDataURL(oFile);
            },

            /**
             * Validate image dimension
             *
             * @param oImageFile
             * @param oCallback
             */
            validateDimension : function(oImageFile, oCallback) {
                let oImage = new Image();
                oImage.src = oImageFile;
                oImage.onload = () => {
                    let mRespond = (oImage.width < 100 && oImage.height < 100) ? true : oImage.src;
                    oCallback(mRespond);
                };
            },

            /**
             * Remove image
             */
            removeImage : function () {
                this.oForm.logo = '';
            },

            /**
             * Change page
             *
             * @param iPage
             */
            changePage : function (iPage) {
                this.oPagination.iOffset = (this.oPagination.iLimit * iPage) - this.oPagination.iLimit;
                this.getCompanies();
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

    .uploadBox {
        outline: 2px dashed #FFFFFF; /* the dash box */
        outline-offset: -10px;
        background: #D8802B;
        color: #FFFFFF;
        padding: 15px 32px;
        cursor: pointer;
        width: 85%;
        display: inline-block;
        margin: 5px;
        text-align:center;
        position: relative;
    }

    .input-file {
        opacity: 0; /* invisible but it's there! */
        width: 100%;
        cursor: pointer;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
    }

    .uploadBox p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 0;
    }

    tbody td img {
        width: 50%;
    }

    .uploadBox img {
        width: 30%;
        margin: auto;
        display: block;
        margin-bottom: 10px;
    }

    .uploadBox img {
        width: 30%;
    }

    tbody td img,
    .uploadBox img {
        margin: auto;
        display: block;
        margin-bottom: 10px;
    }

    .remove-image {
        background-image: unset;
        border: none;
        color: black;
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
