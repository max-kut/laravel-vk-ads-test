<template>
    <v-app v-if="authUser">
        <v-toolbar app fixed>
            <!--<i class="app&#45;&#45;icon icon icon-menu" @click.stop="handleDrawerToggle"></i>-->
            <v-toolbar-title>
                <span v-html="header"></span>
                <br>

            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu bottom left offset-y
                    transition="slide-x-transition">
                <span slot="activator">
                    <span>
                        {{ authUser.first_name }}
                        {{ authUser.last_name }}
                    </span>
                    <v-avatar
                            color="grey lighten-4">
                        <img v-if="userPhoto" :src="userPhoto" alt="avatar">
                        <img v-else src="@/assets/profile.jpg" alt="avatar">
                    </v-avatar>
                </span>
                <v-list>
                    <v-list-tile @click="goLogout">
                        <v-list-tile-action>
                            <font-awesome-icon :icon="faSignOutAlt"/>
                        </v-list-tile-action>
                        <v-list-tile-title>
                            Выход
                        </v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>

        </v-toolbar>
        <v-content>
            <v-container fluid>
                <div :is="$CONTEXT.viewName"></div>
            </v-container>
        </v-content>
        <!-- <v-footer app></v-footer> -->
    </v-app>
    <LoginView v-else></LoginView>
</template>

<script>
    import faUserCircle from '@fortawesome/fontawesome-free-solid/faUserCircle'
    import faSignOutAlt from '@fortawesome/fontawesome-free-solid/faSignOutAlt'

    const loadView = name => {
        return () => import(`@/views/${name}.vue`)
    };

    const LoginView = loadView('LoginView');
    const IndexView = loadView('IndexView');
    const AccountsListView = loadView('AccountsListView');
    const AccountView = loadView('AccountView');
    const AdsView = loadView('AdsView');

    export default {
        name: 'App',
        components: {
            IndexView,
            AccountsListView,
            LoginView,
            AccountView,
            AdsView
        },
        data() {
            return {
                faUserCircle,
                faSignOutAlt,
                drawer: true,
            }
        },
        computed: {
            header() {
                return this.$CONTEXT.head
            },
            userPhoto() {
                if (this.$store.state.authUser.photo_url) {
                    return this.$store.state.authUser.photo_url
                }
                return false
            },
            authUser(){
                return this.$store.state.authUser
            },
        },
        methods: {
            handleDrawerToggle() {
                this.drawer = !this.drawer
            },
            goProfile() {

            },
            goLogout() {
                window.location = "/logout"
            }
        }
    }
</script>

<style lang="scss">
    @import "@/mixins.scss";

    @include reset();
    *,
    *:before,
    *:after {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        height: 100%;
        width: 100%;
        max-width: 100%;
    }

    .app {
        &--icon {
            cursor: pointer;
            position: relative;
            &:after {
                position: absolute;
                content: '';
                left: 25%;
                top: 25%;
                width: 50%;
                height: 50%;
                border-radius: 50%;
            }
            &:active {
                &:after {
                    animation: pulse .2s;
                }
            }
        }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(blue, 0.4);
        }
        70% {
            box-shadow: 0 0 0 16px rgba(blue, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(blue, 0);
        }
    }

    .he-tree {
        // border: 1px solid #ccc;
        padding: 10px;
        min-width: 300px;
        // margin-right: 30px;
    }

    .tree-node {
    }

    .tree-node-inner {
        padding: 5px;
        border: 1px solid #ccc;
        cursor: pointer;
    }

    .draggable-placeholder {

    }

    .draggable-placeholder-inner {
        border: 1px dashed #0088F8;
        box-sizing: border-box;
        background: rgba(0, 136, 249, 0.09);
        color: #0088f9;
        text-align: center;
        padding: 0;
        display: flex;
        align-items: center;
    }
</style>
