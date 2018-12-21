<template>
    <div>
        <panel-item :field="warehouse"/>
        <panel-item class="border-none" :field="address"/>
        <button
                @click.prevent="goShipment"
                class="flex-no-shrink ml-auto w-full btn btn-default btn-primary inline-flex justify-center items-center">
            <svg class="fill-current w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                 height="24">
                <path class="heroicon-ui"
                      d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/>
            </svg>
            <span>{{btnTitle}}</span>
        </button>
        <component
                v-if="slot"
                :is="slot"
                v-bind="$props"
                :resource="resource"
        >
        </component>
    </div>
</template>

<script>
  import Helper from '../helper'
  import toPut from './toPut'

  export default {
    name: 'inventory-incomes',
    mixins: [Helper],

    components: {
      toPut
    },
    methods: {
      goShipment () {
        const {resourceName, resourceId} = this.$route.params
        this.alreadyShipped ?
          this.$router.push({
            name: 'shipment.detail', params: {
              resourceName,
              resourceId
            }
          }) :
          this.$router.push({
            name: 'shipment.update', params: {
              resourceName,
              resourceId
            }
          })
      },
    },
    computed: {
      btnTitle () {
        return this.alreadyShipped ? '物流详情' : '发货'
      },
      slot () {
        return _.get(this, 'field.slot', false)
      }
    },
    async created () {
      this.resource = await this.fetchResource()
      this.setAddress()
    }
  }
</script>

<style scoped>

</style>