<template>
    <panel-item :field="field">
        <div slot="value" v-if="loaded">
            <translation-detail-field
                    :class="{'border-b border-40':attributes.length>index+1}"
                    v-for="(attribute,index) in attributes" :key="attribute.name" :field="attribute">
            </translation-detail-field>
        </div>
    </panel-item>
</template>

<script>
  import TranslationDetailField from './TranslationDetailField'
  import Helper from '../helper'

  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    mixins: [Helper],
    components: {
      TranslationDetailField
    },

    data () {
      return {
        loaded: false
      }
    },
    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue () {
        this.value = this.field.value || ''
      },
    },
    mounted () {
      this.setInitialValue()
      if (this.field.attributes) {
        this.fillAttributesValues(this.field.attributes)
      }
      this.loaded = true
    }
  }
</script>
