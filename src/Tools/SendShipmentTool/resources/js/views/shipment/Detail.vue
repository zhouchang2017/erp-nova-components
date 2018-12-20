<template>
    <loading-view :loading="initialLoading">
        <heading class="mb-3">Address</heading>
        <card class="overflow-hidden border border-50 mb-6">
            <div class="py-3 px-6">
                <panel-item :field="warehouse"/>
                <panel-item class="border-none" :field="address"/>
            </div>
        </card>

        <heading class="mb-3">Send Shipment Edit</heading>
        <card class="overflow-hidden border border-50 mb-6">
            <variant-list-table :items="resource.items" @shipment="shipment" detail></variant-list-table>
        </card>
    </loading-view>
</template>

<script>
  import Helper from '../../helper'
  import VariantListTable from '../../components/VariantListTable'
  import TrackNumberInput from '../../components/TrackNumberInput'

  export default {
    name: 'shipment-detail',
    mixins: [Helper],
    components: {
      VariantListTable,
      TrackNumberInput
    },
    async created () {
      this.resource = await this.fetchResource()
      // this.initItemUnit()
      this.setAddress()
      this.initialLoading = false
    }
  }
</script>

<style scoped>

</style>