<template>
    <div>
        <div class="flex">
            <div class="w-1/5 py-6 px-8">
                <label class="inline-block text-80 pt-2 leading-tight">
                    {{field.name}}
                </label>
            </div>
            <div class="w-1/2 py-6 px-8">
                <el-alert
                        v-if="attributes.length === 0"
                        title="该分类暂无匹配属性"
                        type="error"
                        :closable="false">
                </el-alert>
            </div>
        </div>
        <translation-form-field
                v-for="attribute in attributes" :key="attribute.name" :field="attribute">
        </translation-form-field>
    </div>

</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova'
  import TranslationFormField from './TranslationFormField'

  export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
      TranslationFormField
    },

    data () {
      return {
        eventResponse: null,
        params: 'taxon',
        attributes: []
      }
    },

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue () {
        this.value = this.field.value || ''
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill (formData) {
        formData.append(this.field.attribute, this.value || '')
      },

      /**
       * Update the field's internal value.
       */
      handleChange (value) {
        this.value = value
      },

      handleEventResponseChange () {
        this.fetchRequest().then(({data}) => {
          this.attributes = data
        })
      },

      registerListener () {
        const eventKey = _.get(this, 'field.eventKey', false)
        if (eventKey) {
          Nova.$on(eventKey, value => {
            this.eventResponse = value
            this.handleEventResponseChange()
          })
        }
      },
      fetchRequest () {
        return Nova.request().get(this.api, {
          params: {
            [this.params]: this.eventResponse
          }
        })
      }
    },
    computed: {
      api () {
        return `${Nova.config['erp-prefix']}/${_.get(this, 'field.api', 'product-attributes')}`
      }
    },
    mounted () {
      this.registerListener()
      this.params = _.get(this, 'field.params', this.params)
    }
  }
</script>
