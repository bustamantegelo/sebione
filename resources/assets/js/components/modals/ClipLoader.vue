<template>
    <!-- Spinner Div -->
    <div class="v-spinner" v-show="loading">

        <!-- Spinner Div Render -->
        <div class="v-clip" v-bind:style="spinnerStyle">
        </div>
    </div>
</template>

<script>
    export default {
        /**
         * Name of Component
         */
        name : 'ClipLoader',

        /**
         * Data to pass in the parent
         */
        props : {
            /**
             * Loading condition
             */
            loading : {
                type    : Boolean,
                default : true
            },

            /**
             * Color of spinner
             */
            color   : {
                type    : String,
                default : '#5dc596'
            },

            /**
             * Size of spinner
             */
            size    : {
                type    : String,
                default : '35px'
            },

            /**
             * radius of spinner
             */
            radius  : {
                type    : String,
                default : '100%'
            }
        },

        /**
         * Methods that track changes on data
         */
        computed : {
            /**
             * Initialize the design of spinner
             * @returns {{borderColor: string, borderRadius: (default.props.radius|{default, type}), borderWidth: string, background: string, width: (default.props.size|{default, type}), borderStyle: string, height: (default.props.size|{default, type})}}
             */
            spinnerStyle () {
                return {
                    height       : this.size,
                    width        : this.size,
                    borderWidth  : '5px',
                    borderStyle  : 'solid',
                    borderColor  : this.color + ' ' + this.color + ' transparent',
                    borderRadius : this.radius,
                    background   : 'transparent'
                }
            }
        }
    }
</script>

<style>
    .v-spinner {
        text-align: center;

    }
    .v-spinner .v-clip {
        -webkit-animation: v-clipDelay 0.75s 0s infinite linear;
        animation: v-clipDelay 0.75s 0s infinite linear;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        display: inline-block;
    }
    @-webkit-keyframes v-clipDelay {
        0% {
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50% {
            -webkit-transform: rotate(180deg) scale(0.8);
            transform: rotate(180deg) scale(0.8);
        }
        100% {
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }
    @keyframes v-clipDelay {
        0% {
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50% {
            -webkit-transform: rotate(180deg) scale(0.8);
            transform: rotate(180deg) scale(0.8);
        }
        100% {
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }
</style>
