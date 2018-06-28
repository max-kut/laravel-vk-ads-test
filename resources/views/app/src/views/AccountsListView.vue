<template>
    <v-data-table
            :headers="headers"
            :items="items"
            hide-actions
            class="elevation-1"
    >
        <template slot="items" slot-scope="{ item, index}">
            <td>
                <a :href="'/accounts/'+item.account_id">{{item.account_name}}</a>
            </td>
            <td>
                {{ roles[item.access_role] }}
            </td>
            <td>
                {{ accountTypes[item.account_type] }}
            </td>
            <td>
                {{ item.account_status ? 'активен' : 'неактивен' }}
            </td>
        </template>
    </v-data-table>
</template>

<script>
    export default {
        name: "AccountsListView",
        data(){
            return {
                headers: [
                    {
                        text: 'Название кабинета',
                        value: 'account_name',
                        sortable: false
                    }, {
                        text: 'уровень доступа',
                        value: 'access_role',
                        sortable: false
                    }, {
                        text: 'тип рекламного кабинета',
                        value: 'account_type ',
                        sortable: false
                    }, {
                        text: 'статус рекламного кабинета',
                        value: 'account_status ',
                        sortable: false
                    },
                ]
            }
        },
        computed:{
            items(){
                return this.$store.state.currentViewData.accounts
            },
            roles(){
                return {
                    admin: 'главный администратор',
                    manager: 'администратор',
                    reports: 'наблюдатель'
                }
            },
            accountTypes(){
                return {
                    general: 'обычный',
                    agency: 'агентский'
                }
            }
        },
        methods:{
        }
    }
</script>