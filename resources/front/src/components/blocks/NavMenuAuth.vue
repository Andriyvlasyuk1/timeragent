<template>
    <div>
        <el-header>
            <el-row>
                <el-col :span="16" :offset="4">
                    <span class="logo">
                        <router-link to="/">
                            <img class="logo" src="../../assets/images/logo.svg" alt="logo"/>
                        </router-link>
                    </span>
                    <el-menu
                            class="el-menu-demo"
                            :router="true" :default-active="$route.path"
                            mode="horizontal"
                        >
                        <el-menu-item :index="generateUrl('tasks')">
                            <router-link :to="generateUrl('tasks')">
                                <span>Tasks</span>
                            </router-link>
                        </el-menu-item>
                        <el-menu-item :index="generateUrl('members')" v-if="isOrganization">
                            <router-link :to="generateUrl('members')">
                                <span>Members</span>
                            </router-link>
                        </el-menu-item>
                        <el-menu-item :index="generateUrl('projects')" v-if="isOrganization">
                            <router-link :to="generateUrl('projects')">
                                <span>Projects</span>
                            </router-link>
                        </el-menu-item>
                        <el-menu-item :index="generateUrl('teams')" v-if="isOrganization">
                            <router-link :to="generateUrl('teams')">
                                Teams
                            </router-link>
                        </el-menu-item>
                        <el-menu-item :index="generateUrl('clients')" v-if="isOrganization">
                            <router-link :to="generateUrl('clients')">
                                <span>Clients</span>
                            </router-link>
                        </el-menu-item>
                        <el-submenu index="3" v-if="isPersonal">
                            <template slot="title">Manage</template>
                            <el-menu-item :index="generateUrl('teams')" v-if="isPersonal">
                                <router-link :to="generateUrl('teams')">
                                    Teams
                                </router-link>
                            </el-menu-item>
                            <el-menu-item :index="generateUrl('projects')" v-if="isPersonal">
                                <router-link :to="generateUrl('projects')">
                                    <span>Projects</span>
                                </router-link>
                            </el-menu-item>
                        </el-submenu>
                        <el-submenu index="4" v-if="isPersonal">
                            <template slot="title">{{ user.name }}</template>
                            <div v-for="organization in user.organizations">
                                <el-menu-item :index="`/organization/${organization.id}/tasks`">
                                    <router-link :to="{
                                            name: 'organization',
                                            params: {
                                                segment: 'organization',
                                                organizationId: organization.id
                                            }
                                        }">
                                        {{ organization.name }}
                                    </router-link>
                                </el-menu-item>
                            </div>
                            <el-menu-item :index="generateUrl('organizations/new')">
                                <router-link :to="generateUrl('organizations/new')">
                                    <i class="el-icon-plus"></i> New Organization
                                </router-link>
                            </el-menu-item>
                            <el-menu-item :index="generateUrl('profile')">
                                <router-link :to="generateUrl('profile')">
                                    Profile
                                </router-link>
                            </el-menu-item>
                            <el-menu-item index="" @click="logout">
                                <router-link to="" >
                                    <span>Log out</span>
                                </router-link>
                            </el-menu-item>
                        </el-submenu>
                        <el-submenu index="4" v-if="isOrganization">
                            <template slot="title">{{ organization.name }}</template>
                            <div v-for="user in organization.users">
                                <el-menu-item index="/personal/tasks">
                                    <router-link
                                            :to="{
                                                name: 'tasks',
                                                params: {
                                                    segment: 'personal'
                                                }
                                            }">
                                        {{ user.name }}
                                    </router-link>
                                </el-menu-item>
                            </div>
                        </el-submenu>
                    </el-menu>
                </el-col>
            </el-row>
        </el-header>

    </div>
</template>

<script>
    import ElRow from 'element-ui/packages/row/src/row';
    import moment from 'moment';
    import { mapGetters, mapActions } from 'vuex';

    export default {
        components: {
            ElRow,
        },
        data() {
            return {
                isOpened: null,
                task    : {
                    id         : 0,
                    description: '',
                    project_id : null,
                    task_id    : null,
                },
            };
        },
        created() {
            if (this.$route.params.segment === 'organization') {
                localStorage.setItem('profile', 'organization');
                localStorage.setItem('organizationId', this.$route.params.organizationId);
                if (this.organization.id === undefined) {
                    this.getOneOrganization({
                        id: this.$route.params.organizationId,
                    });
                }
            }
            if (this.$route.params.segment === 'personal') {
                localStorage.setItem('profile', 'personal');
                if (this.user.id === undefined) {
                    this.getUser();
                }
            }
        },
        destroyed() {
            if (this.$route.params.segment === 'personal') {
                this.$store.dispatch('clearOrganization');
            }
            if (this.$route.params.segment === 'organization') {
                this.$store.dispatch('clearUser');
            }
        },
        computed: {
            ...mapGetters([
                'organization',
                'user',
            ]),
            date() {
                return moment().format('YYYY-MM-DD');
            },
            isOrganization() {
                return this.profile === 'organization';
            },
            isPersonal() {
                return this.profile === 'personal';
            },
            profile() {
                return localStorage.getItem('profile');
            },
        },
        methods: {
            ...mapActions([
                'getUser',
                'getOneOrganization',
            ]),
            showSubMenu(name) {
                this.isOpened = (this.isOpened === null) ? name : null;
            },
            subIsActive(input) {
                const paths = Array.isArray(input) ? input : [input];

                // current path starts with this path string
                return paths.some(path => this.$route.path.indexOf(path) === 0);
            },
            hideSubMenu() {
                setTimeout(() => {
                    this.isOpened = null;
                }, 300);
            },
            generateUrl(url) {
                if (this.isOrganization) {
                    return `/${this.$route.params.segment}/${this.$route.params.organizationId}/${url}`;
                }
                return `/${this.$route.params.segment}/${url}`;
            },
            logout() {
                this.$store.dispatch('logout');
                this.$router.go('/');
            },
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" rel="stylesheet/scss" scoped>

    .el-menu-item a {
        display: block;
        width: 100%;
    }

    .control-buttons {
        padding: 17px;
    }
    .logo {
        width  : 240px;
        height : 70px;
        float: left;
    }

    .el-menu-demo {
        float: right;
        margin-right: 8em;
    }

    .el-header {
        background-color: #fff;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    body {
        font-family : "Open Sans", sans-serif;
        font-size   : 14px;
        line-height : 1.42857;
        color       : #525252;

        .dropdown-menu {
            position           : absolute;
            top                : 100%;
            left               : 0;
            z-index            : 1000;
            display            : none;
            min-width          : 160px;
            padding            : 6px 0;
            margin             : 5px 0 0;
            list-style         : none;
            font-size          : 14px;
            text-align         : left;
            background-color   : #fff;
            border             : 1px solid rgba(0, 0, 0, .15);
            border-radius      : 3px;
            -webkit-box-shadow : 0 6px 12px rgba(0, 0, 0, .175);
            box-shadow         : 0 6px 12px rgba(0, 0, 0, .175);
            background-clip    : padding-box;
        }

        .btn {
            font-size : 18px;
        }

        header {

            display          : block;
            flex-grow        : 0;
            /*background-color : #464646;*/
            box-shadow       : 0 2px 3px 0 rgba(47, 47, 47, .25) !important;

            .fa {
                font-size : 0.9em;
            }

            .navbar {
                display         : flex;
                justify-content : space-between;
                margin          : auto !important;
                border          : 0 !important;
                border-radius   : unset;

                img {
                    vertical-align : middle;
                }

                .dropdown {

                    .dropdown-menu {

                        position           : absolute;
                        left               : 0;
                        z-index            : 1000;
                        display            : none;
                        min-width          : 160px;
                        padding            : 6px 0;
                        margin             : 5px 0 0;
                        list-style         : none;
                        font-size          : 14px;
                        text-align         : left;
                        border-radius      : 3px;
                        -webkit-box-shadow : 0 6px 12px rgba(0, 0, 0, .175);
                        background-clip    : padding-box;
                        background-color   : #393939;
                        border             : none;
                        top                : 65px;
                        box-shadow         : 2px 2px 3px 0 rgba(47, 47, 47, .25) !important;

                        li {

                            a {
                                color       : rgba(255, 255, 255, .5);
                                display     : block;
                                padding     : 6px 25px;
                                font-weight : 400;
                            }

                            a:hover {
                                color            : rgba(255, 255, 255, .75);
                                background-color : #444;
                            }
                        }

                    }

                    .open {
                        display : block;

                        a {
                            background-color : #393939;
                            color            : #fff;
                        }
                    }

                }

                * {
                    display        : inline-block;
                    vertical-align : top;
                    margin         : 0;
                    line-height    : inherit;
                }

                .logo {
                    line-height : 70px;
                }

                .timer-buttons {

                    .btn {
                        height           : 48px;
                        width            : 48px;
                        min-width        : 0;
                        margin           : 11px 3px;
                        border-radius    : 48px;
                        box-shadow       : 0 2px 6px rgba(0, 0, 0, .2) !important;

                        color            : #fff;
                        background-color : #00bc6a;
                    }

                    .btn-timer-start:hover {
                        background-color : #00a35c;
                        border-color     : #00a35c;
                    }

                    .btn-timer-continue:hover {
                        background-color : #00a35c;
                        border-color     : #00a35c;
                    }

                    .btn-timer-stop {
                        height           : 36px;
                        width            : 36px;
                        margin           : 17px 3px;
                        border-radius    : 36px;
                        padding          : 6px 0;

                        color            : #fff;
                        background-color : #e26a6a;
                    }

                    .btn-timer-stop:hover {
                        background-color : #de5555;
                        border-color     : #de5555;
                    }
                }

            }

            .navbar-default {

                background-color : #464646;

                .navbar-nav {

                    a.router-link-active {
                        color            : #fff;
                        background-color : #393939;
                        border-top       : 4px solid transparent;
                        border-bottom    : 4px solid #fff;
                        padding-top      : 21px;
                        padding-bottom   : 21px;
                    }
                    a.router-link-active:hover {
                        color : #fff;
                    }
                    .router-link-active {

                        a {
                            color            : #fff;
                            background-color : #393939;
                            border-top       : 4px solid transparent;
                            border-bottom    : 4px solid #fff;
                            padding-top      : 21px;
                            padding-bottom   : 21px;
                        }
                        .open {
                            a.router-link-active {
                                color            : #fff;
                                background-color : #393939;
                            }

                            a.router-link-active:hover {
                                color : #fff;
                            }

                            a {
                                display     : block;
                                padding     : 6px 25px;
                                font-weight : 400;
                                color       : #333;
                                color       : rgba(255, 255, 255, .5);
                                border      : none;
                            }
                        }
                    }

                    .open a {
                        display     : block;
                        padding     : 6px 25px;
                        font-weight : 400;
                        color       : #333;
                        color       : rgba(255, 255, 255, .5);

                    }

                    /*.open {*/
                    /*a.router-link-active {*/
                    /*color: #fff;*/
                    /*background-color: #393939;*/
                    /*}*/
                    /*a.router-link-active:hover {*/
                    /*color: #fff;*/
                    /*}*/
                    /*}*/

                    a.expanded {
                        background-color : #393939;
                        color            : #fff;
                    }

                    a:focus {
                        color            : rgba(255, 255, 255, .75);
                        background-color : #404040;
                    }

                    li {

                        a {
                            color          : rgba(255, 255, 255, .5);
                            font-weight    : bold;
                            max-height     : 70px;
                            padding-top    : 25px;
                            padding-bottom : 25px;
                        }
                    }
                }
            }
        }
    }

</style>

<style>
    .logo {
        position: relative;
    }
</style>
