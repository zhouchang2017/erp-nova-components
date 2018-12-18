<template>
    <div class="border-b border-40">
        <div class="flex">
            <div class="w-1/5 py-6 px-8">
                <label class="inline-block text-80 pt-2 leading-tight">
                    {{field.name}}
                </label>
            </div>
            <div class="w-1/2 py-6 px-8">
                <el-alert
                        v-if="options.length === 0"
                        title="该分类暂无匹配销售"
                        type="error"
                        :closable="false">
                </el-alert>
                <el-checkbox-group :disabled="edit" @change="changeChecked" v-else class="flex flex-wrap"
                                   v-model="checked">
                    <el-checkbox v-for="option in options" class="w-1/3 py-2 flex items-center" :label="option"
                                 :key="option.id">{{option.name}}
                    </el-checkbox>
                </el-checkbox-group>
            </div>
        </div>
        <option-item
                v-for="attribute in checked"
                :key="attribute.name"
                :field="attribute"
                :defaultValues="getDefaultOptionValues(attribute.id)"
                @checked-option-change="checkedOptionChange"
        >
        </option-item>
    </div>

</template>

<script>
  import { FormField, HandlesValidationErrors, Minimum } from 'laravel-nova'
  import OptionItem from './OptionItem'
  import Helper from '../helper'

  export default {
    mixins: [FormField, HandlesValidationErrors, Helper],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
      OptionItem
    },

    data () {
      return {
        eventResponse: null,
        params: 'taxon',
        options: [],
        checked: [],
        changeOptions: {}
      }
    },

    watch: {
      changeOptions: {
        handler: function (val) {
          Nova.$emit(_.get(this, 'field.emit', 'option-change'), {
            options: this.checked,
            optionValues: val
          })
        },
        deep: true,
        immediate: false
      }
    },

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue () {
        this.value = this.field.value || []
        this.filterOptionValues()
      },



      initCheckedValue () {
        this.checked = this.options.filter(option => this.value.map(item => item.id).includes(option.id))
      },

      changeChecked (val) {
        Nova.$emit(_.get(this, 'field.emit', 'option-change'), {
          options: val,
          optionValues: this.changeOptions
        })
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill (formData) {
        this.checked.forEach((option, index) => {
          formData.append(this.field.attribute + '[' + index + ']', option.id || '')
        })
      },

      /**
       * Update the field's internal value.
       */
      handleChange (value) {
        this.value = value
      },

      addOptionOriginProp (options) {
        return options.map(option => {

          if (option.values && option.values.length > 0) {
            const values = this.getDefaultOptionValues(option.id)
            // 该 option 存在可选值，合并已存在数据
            const groupBy = _.groupBy(values, 'value_id')
            option.values = option.values.map(value => {
              if (groupBy.hasOwnProperty(value.id)) {
                return {
                  id: null,
                  option_id: option.id,
                  value_id: value.id,
                  selected: value,
                  unique_code: value.unique_code,
                  origins: groupBy[value.id]
                }
              }
              // const origin = values.find(item => item.value_id === value.id)
              return {
                id: null,
                option_id: option.id,
                value_id: value.id,
                selected: value,
                unique_code: value.unique_code
              }
            })
          }
          return option
        })
      },

      /*
      * 监听字段变化回调
      * */
      handleEventResponseChange () {
        this.fetchRequest().then(({data}) => {
          this.options = this.addOptionOriginProp(data)
          this.initCheckedValue()
        })
      },

      /*
      * 注册监听
      * */
      registerListener () {
        Nova.$on(this.eventName, value => {
          this.eventResponse = value
          if (value) {
            this.handleEventResponseChange()
          }
        })
      },

      /*
      * 删除监听
      * */
      deleteListener () {
        Nova.$off(this.eventName)
      },
      /*
      * 请求options
      * */
      fetchRequest () {
        return Minimum(Nova.request().get(this.api, {
          params: {
            [this.params]: this.eventResponse
          }
        }))
      },

      /*
      * option 选项变化回调
      * */
      checkedOptionChange ({option, id}) {
        if (option.length === 0) {
          this.$delete(this.changeOptions, id)

          return
        }
        this.$set(this.changeOptions, id, option)
      }
    },
    computed: {
      api () {
        return `${Nova.config['erp-prefix']}/${_.get(this, 'field.api', 'product-options')}`
      },
      edit () {
        return this.$route.name === 'edit'
      },
      eventName () {
        return _.get(this, 'field.eventKey', 'taxon-change')
      }
    },
    mounted () {
      this.registerListener()
      this.params = _.get(this, 'field.params', this.params)
    },
    beforeDestroy () {
      this.deleteListener()
    }
  }
</script>
