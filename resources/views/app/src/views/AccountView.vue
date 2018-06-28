<template>
    <div>
        <a href="/accounts">К списку кабинетов</a>
        <h1>Список рекламных кампаний</h1>
        <v-data-table
                :headers="headers"
                :items="items"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="{ item, index}">
                <td>
                    <a :href="basePath+item.id">{{item.name}}</a>
                </td>
                <td>
                    {{ companyTypes[item.type] }}
                </td>
                <td>
                    {{ companyStatuses[item.status] }}
                </td>
                <td>
                    {{ item.day_limit + ' руб' }}
                </td>
                <td>
                    {{ item.all_limit + ' руб' }}
                </td>
            </template>
        </v-data-table>
    </div>
</template>

<script>
    import {companyTypes, companyStatuses} from "@/utils"
    export default {
        name: "AccountView",
        data(){
            return {
                headers: [
                    {
                        text: 'Название кампании',
                        value: 'name',
                        sortable: false
                    }, {
                        text: 'тип кампании',
                        value: 'type',
                        sortable: false
                    }, {
                        text: 'статус кампании',
                        value: 'status ',
                        sortable: false
                    }, {
                        text: 'дневной лимит кампании в рублях',
                        value: 'day_limit ',
                        sortable: false
                    }, {
                        text: 'общий лимит кампании в рублях',
                        value: 'all_limit ',
                        sortable: false
                    },
                ]
            }
        },
        computed:{
            items(){
                return this.$store.state.currentViewData.campaigns
            },
            basePath(){
                return location.pathname + '/'
            },
            companyTypes(){
                return companyTypes
            },
            companyStatuses(){
                return companyStatuses
            }
        },
    }
</script>