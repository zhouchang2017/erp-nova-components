<template>
    <div>
        <div class="flex items-center">
            <div class="w-1/5 py-3 px-8">
            <span
                    class="inline-block bg-primary-30% rounded-sm px-3 py-1 text-80 text-sm">
                {{field.name}}
            </span>

            </div>
            <div class="w-1/2 py-3 px-8 flex">
                <button
                        @click.prevent="add"
                        class="btn-outline inline-flex items-center rounded-lg cursor-pointer text-70 hover:text-primary">
                    <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                         height="24">
                        <path class="heroicon-ui"
                              d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex items-center">
            <div class="w-1/5 py-3 px-8">

            </div>
            <div class="w-4/5 py-3 px-8">
                <el-checkbox-group class="flex flex-wrap" v-model="checked" v-if="options.length>0">
                    <option-item-value
                            v-for="(option,index) in options"
                            :key="index"
                            :option="option"
                            @edit="()=>edit(option,index)"
                            @destroy="destroy"
                    ></option-item-value>
                </el-checkbox-group>
                <el-alert
                        v-else
                        title="暂无可选属性，请添加自定义属性值"
                        :closable="false"
                        type="info">
                </el-alert>
            </div>
            <add-option-value-dialog
                    @confirm="confirm"
                    :defaultOption="currentEdit"
                    v-model="openEditDialog"></add-option-value-dialog>
        </div>
    </div>
</template>

<script>
  import AddOptionValueDialog from './AddOptionValueDialog'

  import OptionItemValue from './OptionItemValue'

  export default {
    name: 'option-item',

    props: ['field', 'defaultValues'],

    provide () {
      return {
        item: this.field
      }
    },

    components: {
      AddOptionValueDialog,
      OptionItemValue
    },

    data () {
      return {
        options: [],
        checked: [],
        openEditDialog: false,
        currentEdit: {}
      }
    },
    watch: {
      checked: {
        handler: function (v) {
          this.$emit('checked-option-change', {option: v, id: this.field.id})
        },
        immediate: true
      }
    },
    methods: {
      /*
      * 销售属性选项初始化
      * */
      initOptions () {
        this.options = (this.field.values || []).slice()
      },
      /*
      * 初始化已选中属性
      * */
      initChecked () {
        const valueIds = _.filter(_.get(this, 'defaultValues', []), 'value_id').map(item => item.value_id)
        this.checked = _.get(this,'field.values',[]).filter(option => valueIds.includes(option.value_id))
      },
      /*
      * 初始化自定义销售属性值
      * */
      initCustomValues () {
        const customValues = _.filter(_.get(this, 'defaultValues', []), ['selected', null])
        this.options.push(...customValues)
        this.checked.push(...customValues)
      },
      /*
      * 激活模态框 新建状态
      * */
      add () {
        this.currentEdit = {}
        this.openEditDialog = true
      },
      /*
      * 激活模态框 编辑状态
      * */
      edit (option, index) {
        this.currentEdit = option
        this.openEditDialog = true
      },
      /*
      * 模态框确认事件
      * */
      confirm ({value, defaultOption}) {
        defaultOption ?
          this.editCustomOptionValue(value, defaultOption) :
          this.addCustomOptionValue(value)
      },
      /*
      * 添加自定义销售属性值
      * */
      addCustomOptionValue (value) {
        const translations = []
        for (let locale in value) {
          translations.push({
            id: null,
            locale_code: locale,
            value: value[locale]
          })
        }
        const item = {
          id: null,
          option_id: this.field.id,
          selected: null,
          value_id: null,
          value: value[this.indexLocale],
          translations,
          unique_code:_.uniqueId(`${value[this.indexLocale]}_`)
        }
        this.options.push(item)
        this.checked.push(item)
      },
      /*
      * 修改自定义销售属性值
      * */
      editCustomOptionValue (value, item) {
        // const option = this.options.find(option => option.id === item.id)
        item.translations.forEach(translation => {
          for (let locale in value) {
            if (translation.locale_code === locale) {
              _.set(translation, 'value', value[locale])
            }
          }
        })
        if(_.has(item,'origins')){
          item.origins.forEach(attr=>{
            attr.translations.forEach(translation => {
              for (let locale in value) {
                if (translation.locale_code === locale) {
                  _.set(translation, 'value', value[locale])
                }
              }
            })
          })
        }
      },
      /*
      * 删除自定义属性值
      * */
      destroy (option) {
        const index = this.options.findIndex(item => item === option)
        this.checked.splice(index, 1)
        this.options.splice(index, 1)
      }
    },
    beforeDestroy () {
      this.checked = []
      this.$emit('checked-option-change', {option: this.checked, id: this.field.id})
    },
    mounted () {
      this.initOptions()
      this.initChecked()
      this.initCustomValues()
    }
  }
</script>

<style>
    .el-checkbox + .el-checkbox {
        margin-left: 0;
    }
</style>