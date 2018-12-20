<template>
    <component
            :is="resourceName"
            :resourceName="resourceName"
            :resourceId="resourceId"
            :field="field"
    ></component>
</template>

<script>
  import InventoryIncomes from './InventoryIncomes'
  import InventoryExpends from './InventoryExpends'

  export default {
    props: ['resourceName', 'resourceId', 'field','resource'],

    components: {
      InventoryIncomes,
      InventoryExpends
    }
  }
</script>
