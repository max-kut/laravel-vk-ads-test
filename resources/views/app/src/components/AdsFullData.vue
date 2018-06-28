<template>
    <v-card style="padding: 10px">
        <v-card-title primary-title>
            <div>
                <h3 class="headline mb-0">{{ localItem.name }}</h3>
                <div>
                    <a href="#" @click="isEdit = true">редактировать</a></div>
            </div>
        </v-card-title>
        <textarea rows="5"
                  :style="{
                    border: '1px solid #ccc',
                    width: '100%'
                  }"
                  v-model="description"
                  placeholder="описание"
                  @keypress="onInput"
                  :disabled="!isEdit"></textarea>
        <v-card-actions v-if="isEdit">
            <v-btn flat color="orange" @click="onSave">Сохранить</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: 'AdsFullData',
        props:{
            item: Object
        },
        data(){
            return {
                isChange: false,
                isEdit: false,
                localItem: Object.assign({},this.item)
            }
        },
        computed:{
           description:{
               get(){
                   return this.localItem.description
               },
               set(val){
                   if(val.length <= 100){
                       this.localItem.description = val
                   } else {
                       this.localItem.description = val.slice(0,100)
                   }
               }
           }
        },
        watch:{
            item(val){
                this.localItem = Object.assign({},val)
            }
        },
        methods:{
            onSave(){
                this.isEdit = false
                this.$emit('saved')
                this.$POST('/save-ad/'+this.localItem.id, {
                    description: this.localItem.description
                }).done(res=>{
                    items = this.$store.state.currentViewData.ads.slice()
                    items.forEach((el,i) => {
                        if(el.id == this.localItem.id){
                            items.splice(i, 1, Object.assign({},items[i],{
                                description: this.localItem.description
                            }))
                        }
                    })
                    this.$store.commit('SET_DATA', Object.assign({},this.$store.state.currentViewData, {
                        ads: items
                    }));
                })
            },
            onInput(e){
                if(e.keyCode !== 8 && e.keyCode !== 46){
                    if(e.target.value.length >= 100){
                        e.preventDefault()
                        return false
                    }
                }
            }
        }
    }
</script>