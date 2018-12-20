<template>
    <panel-item :field="field">
        <p slot="value" class="text-90">{{currentName}}</p>
    </panel-item>
</template>

<script>
  import Helper from '../helper'

  export default {
    mixins: [Helper],
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    computed: {
      currentName () {
        return _.get(this, 'field.value.name', '-')
      }
    },
    watch: {
      id: {
        handler: function (value, oldValue) {
          if (value && value !== oldValue) {
            Nova.$emit(this.eventKey, value)
          }
        },
        immediate: true
      }
    }
  }
</script>
