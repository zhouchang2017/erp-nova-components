<template>
    <panel-item :field="field">
        <div slot="value">
            <translation-detail-field v-for="option in value" :field="option"
                                      :key="option.id"></translation-detail-field>

        </div>
    </panel-item>
</template>

<script>
  import Helper from '../helper'
  import TranslationDetailField from './TranslationDetailField'

  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    mixins: [Helper],

    components: {
      TranslationDetailField
    },

    data () {
      return {
        value: []
      }
    },

    methods: {
      setInitialValue () {
        this.value = this.field.value.map(option => {
          option.values = this.uniqueOptionValues(
            this.getDefaultOptionValues(option.id)
          )

          return option
        })
      },
      uniqueOptionValues (values) {
        const temp = []
        return values.reduce((res, value) => {
          if (!this.isCustom(value)) {
            // selected
            if (!res.find(item => item.value_id === value.value_id)) {
              res.push(value)
            }

          } else {
            if (!temp.find(arr => arr.value === value.value)) {
              temp.push(value)
              res.push({...value, origins: [value]})
            } else {
              const already = res.find(val => val.value === value.value)
              already.origins.push(value)
            }
          }
          return res
        }, [])
      }
    },

    mounted () {
      this.setInitialValue()
    }
  }
</script>
