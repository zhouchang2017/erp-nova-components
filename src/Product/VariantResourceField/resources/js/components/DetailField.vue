<template>
    <selected-table :detail="true" :selected="field.value"/>
</template>

<script>
  import SelectedTable from './SelectedTable'

  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    provide () {
      return {
        resourceName: this.resourceName,
        resourceId: this.resourceId,
        field: this.field,
        resource: this.resource
      }
    },
    components: {
      SelectedTable
    }
  }
</script>
