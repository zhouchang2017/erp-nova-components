<template>
    <span>{{ name }}</span>
</template>

<script>
  import Helper from '../helper'

  export default {
    props: ['resourceName', 'field'],
    mixins: [Helper]
  }
</script>
