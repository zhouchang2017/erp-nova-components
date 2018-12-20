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
            <variant-list-table @all-shipped-change="allShippedChange" :items="resource.items" @shipment="shipment"></variant-list-table>
        </card>
    </loading-view>
</template>

<script>
  import Helper from '../../helper'
  import VariantListTable from '../../components/VariantListTable'
  import TrackNumberInput from '../../components/TrackNumberInput'

  export default {
    name: 'shipment-update',
    mixins: [Helper],
    components: {
      VariantListTable,
      TrackNumberInput
    },

    methods: {

      shipment (item) {
        Nova.request().post(this.shipmentApi, this.filterItemShipped(item))
          .then(({data: {data, ...notify}}) => {
            this.handleChange(data)
            this.$notify(notify)
          })
      },

      allShippedChange(){
        this.$router.replace({
          name: 'shipment.detail', params: {
            resourceName:this.resourceName,
            resourceId:this.resourceId
          }
        })
      },

      filterItemShipped (items) {
        return items.map(item => {
          const clone = _.cloneDeep(item)
          clone.units = clone.units.filter(unit => _.isNull(unit.shipment_track_id))
          const {id, inventory_income_id, units} = clone
          return {id, inventory_income_id, units}
        })
      },
      /**
       * Update the field's internal value.
       */
      handleChange (data) {
        data.forEach(item => {
          const resourceItem = _.find(this.resource.items, ['id', item.id])
          resourceItem.units.forEach((unit, index) => {
            const findUnit = item.units.find(resUnit => resUnit.id === unit.id)
            if (findUnit) {
              resourceItem.units.splice(index,1,findUnit)
              // this.$set(resourceItem, 'units[' + index + ']', findUnit)
            }
          })
        })
      },
    },
    async created () {
      this.resource = await this.fetchResource()
      this.initItemUnit()
      this.setAddress()
      this.initialLoading = false
    }
  }
</script>

<style>

</style>