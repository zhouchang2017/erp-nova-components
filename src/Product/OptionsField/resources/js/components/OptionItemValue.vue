<template>
    <div class="w-1/3 py-2 flex items-center">
        <el-checkbox class="flex items-center " :label="option">{{optionValueName}}
        </el-checkbox>
        <template v-if="option.selected === null">
            <el-popover
                    placement="top"
                    width="160"
                    v-model="showDeletePopover">
                <p>是否确定删除<b>{{optionValueName}}</b>选项</p>
                <div style="text-align: right; margin: 0">
                    <el-button size="mini" type="text" @click="showDeletePopover = false">取消</el-button>
                    <el-button type="primary" size="mini" @click="destroy">确定</el-button>
                </div>
                <button
                        slot="reference"
                        @click.prevent="showDeletePopover = true"
                        class="appearance-none cursor-pointer text-70 hover:text-primary ml-3"
                >
                    <icon width="16" height="16"/>
                </button>

            </el-popover>


            <button
                    @click.prevent="$emit('edit')"
                    class="appearance-none cursor-pointer text-70 hover:text-primary ml-3"
            >
                <icon type="edit" width="16" height="16"/>
            </button>
        </template>
    </div>
</template>

<script>

  export default {
    name: 'option-item-value',

    props: ['option'],

    inject: ['item'],

    data () {
      return {
        showDeletePopover: false
      }
    },

    methods: {

      destroy () {
        this.showDeletePopover = false
        this.$emit('destroy', this.option)
      }
    },
    computed: {
      optionValueName () {
        if(!this.option){
          return '-'
        }
        const isCustom = _.isNull(this.option.selected)
        if(isCustom){
          return _.find(this.option.translations, ['locale_code', this.indexLocale]).value
        }
        const currentLang = _.find(this.option.selected.translations,['locale_code', this.indexLocale])
        return _.get(currentLang,'value',
            _.head(this.option.selected.translations).value
        )
      }
    }
  }
</script>

<style scoped>

</style>