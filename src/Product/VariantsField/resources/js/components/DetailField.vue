<template>
    <panel-item :field="field"/>
</template>

<script>
  import Helper from '../helper'

  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    mixins: [Helper]
  }
</script>
