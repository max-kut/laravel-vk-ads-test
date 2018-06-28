<template>
    <div>
        <a :href="backLink">К списку кабинетов</a>
        <h1>Список объявлений  |
            <at-switch v-model="showDeleted" @change="showDeleted = $event"></at-switch>
            <span>Показывать удаленные объявления</span>

        </h1>
        <v-data-table
                :headers="headers"
                :items="items"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="{ item, index}">
                <td>
                    <a href="#" @click.prevent="openDialog(item)">{{item.name}}</a>
                </td>
                <td>
                    {{ adStatuses[item.status] }}
                </td>
                <td>
                    {{ item.description }}
                </td>
                <td>
                    <v-btn v-if="item.status != 2"
                            @click="onDelete(item)" title="Удалить объявление">X</v-btn>
                </td>
            </template>
        </v-data-table>
        <v-dialog v-model="dialog" persistent max-width="500px">
            <AdsFullData v-if="currentDialogItem"
                         :item="currentDialogItem"
                        @saved="dialog = false"></AdsFullData>
        </v-dialog>
    </div>
</template>

<script>
    import AdsFullData from "@/components/AdsFullData.vue"
    import {adFormats, costTypes, adStatuses} from "@/utils"
    export default {
        name: "AdsView",
        components: {
            AdsFullData
        },
        data(){
            return {
                headers: [
                    {
                        text: 'Название объявления',
                        value: 'name',
                        sortable: false
                    }, {
                        text: 'статус',
                        value: 'status',
                        sortable: false
                    }, {
                        text: 'описание',
                        value: 'description ',
                        sortable: false
                    }
                ],
                dialog: false,
                currentDialogItem: null,
                showDeleted: false
            }
        },
        computed:{
            backLink(){
                return this.$store.state.currentViewData.backLink
            },
            items(){
                return this.$store.state.currentViewData.ads.filter(item => {
                    if(!this.showDeleted){
                        return item.status != 2
                    }
                    return true
                })
            },
            adFormats(){
                return adFormats
            },
            costTypes(){
                return costTypes
            },
            adStatuses(){
                return adStatuses
            },
        },
        methods:{
            openDialog(item){
                this.currentDialogItem = item
                this.dialog = true
            },
            onDelete(item){



                this.$POST('/delete-ad/'+item.id, {
                    accountId: this.$CONTEXT.data.accountId
                }).done(res=>{
                    console.log(res)
                    items = this.$store.state.currentViewData.ads.slice()
                    items.forEach((el,i) => {
                        if(el.id == item.id){
                            items.splice(i, 1, Object.assign({},item,{
                                status: 2
                            }))
                        }
                    })
                    this.$store.commit('SET_DATA', Object.assign({},this.$store.state.currentViewData, {
                        ads: items
                    }));
                })
            }
        }
    }
</script>