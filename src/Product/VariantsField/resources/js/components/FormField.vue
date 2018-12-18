<template>
    <div class="border-b border-40">
        <div class="flex">
            <div class="w-1/5 py-6 px-8">
                <label class="inline-block text-80 pt-2 leading-tight">
                    {{field.name}}
                </label>
            </div>
            <div class="w-4/5 py-6 px-8">
                <el-alert
                        v-if="!canGenSku"
                        title="请选择销售属性"
                        type="error"
                        :closable="false">
                </el-alert>
                <el-alert
                        v-else
                        title="请完善产品销售规格"
                        type="info"
                        :closable="false">
                </el-alert>
            </div>
        </div>
        <div class="flex items-center">
            <div class="w-1/5 py-6 px-8">

            </div>
            <div class="w-4/5 py-6 px-8">
                <sku-table v-model="skuTableSchema" :getOptionName="getOptionName"
                           @cache-sku-item="setSkuItemAttr"
                           :options="options"/>
            </div>

        </div>
    </div>
</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova'

  import SkuTable from './SkuTable'
  import Translation from '../translation'
  import ObjToFormData from '../ObjToFormData'
  import Helper from '../helper'

  export default {
    mixins: [FormField, HandlesValidationErrors, Helper, Translation, ObjToFormData],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
      SkuTable
    },

    data () {
      return {
        checked: [],
        options: [],

      }
    },

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue () {
        this.value = this.setVariantKey(_.get(this, 'field.value', []))
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill (formData) {
        this.objectToFormData(this.skuTableSchema, formData, this.field.attribute)
      },

      /**
       * Update the field's internal value.
       */
      handleChange (value) {
        this.value = value
      },
    },

    computed: {
      canGenSku () {
        return this.checked.length > 0 && this.options.length === this.checked.length
      }
    },

    mounted () {
      Nova.$on(_.get(this, 'field.eventKey', 'option-change'), ({options, optionValues}) => {
        this.options = options
        this.setOptionNames()
        this.checked = _.values(optionValues)
        this.setSkuTableSchema()
      })
    },
    destroyed () {
      Nova.$off(_.get(this, 'field.eventKey', 'option-change'))
    }
  }
</script>
