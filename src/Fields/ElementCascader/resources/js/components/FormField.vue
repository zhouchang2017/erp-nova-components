<template>

    <default-field :field="field" :errors="errors">
        <template slot="field">
            <el-cascader
                    :id="field.name"
                    class="w-full mb-2"
                    :show-all-levels="false"
                    :options="options"
                    :props="optionConfig"
                    :class="errorClasses"
                    :placeholder="field.name"
                    v-model="formatValue"
                    filterable
                    clearable
            ></el-cascader>
            <el-alert
                    :closable="false"
                    title="该分类下扣点"
                    type="info">
            </el-alert>
        </template>
    </default-field>
</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova'

  export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data () {
      return {
        optionConfig: {
          value: 'id',
          label: 'name'
        },
        options: [],
        formatValue: []
      }
    },

    watch: {
      currentValue: {
        handler: function (value, oldValue) {
          if (value && value !== oldValue) {
            Nova.$emit(this.eventKey, value)
          }
        },
        immediate: false
      }
    },

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue () {
        this.value = this.field.value || ''
        this.formatValue = this.field.formatValue || []
        this.options = this.field.options || []
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill (formData) {
        formData.append(this.field.attribute, this.currentValue || '')
      },

      /**
       * Update the field's internal value.
       */
      handleChange (value) {
        this.value = value
      },
    },
    computed: {
      currentValue () {
        return _.last(this.formatValue)
      },
      eventKey () {
        return _.get(this, 'field.eventKey', this.field.attribute + '-value-change')
      }
    }
  }
</script>
