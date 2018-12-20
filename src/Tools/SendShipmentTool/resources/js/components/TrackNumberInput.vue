<template>
    <div>
        <el-input v-if="!disabled" @change="changeInput" placeholder="请输入物流单号" v-model="tracking_number"
                  class="input-with-select">
            <el-select :disabled="disabled" @change="changeSelect" v-model="logistic_id" filterable slot="prepend"
                       placeholder="请选择物流公司">
                <el-option v-for="item in list" :label="item.name" :value="item.id" :key="item.code"></el-option>
            </el-select>

            <slot slot="append" :disabled="disabled" :shipment="currentShipment"></slot>

        </el-input>
        <div v-else class="flex items-center">
            <div class="w-2/5 py-3">
            <span
                    class="inline-block   py-1 text-80 text-sm">
                {{currentLogisticName}}
            </span>

            </div>
            <div class="w-3/5 py-3 px-8">
                <span class="inline-block  px-3 py-1 text-80 text-sm">{{tracking_number}}</span>
            </div>

        </div>
    </div>

</template>

<script>
  import logisticsHelper from '../logisticsHelper'

  export default {
    name: 'track-number-input',

    mixins: [logisticsHelper],

    props: {
      logistics: {
        type: Array,
        default: () => []
      },
      shipment: {
        type: Object,
        default: () => ({id: null, logistic_id: null, tracking_number: ''})
      },
      eventKey: {
        type: String,
      },
      disabled: {
        type: Boolean,
        default: false
      }
    },

    data () {
      return {
        logistic_id: '',
        tracking_number: ''
      }
    },

    methods: {
      initValue ({logistic_id, tracking_number}) {
        this.logistic_id = logistic_id
        this.tracking_number = tracking_number
      },
      changeInput (tracking_number) {
        this.shipment.tracking_number = tracking_number
      },
      changeSelect (logistic_id) {
        console.log(logistic_id)
        this.shipment.logistic_id = logistic_id
      },
      getValue () {
        return this.currentShipment
      }
    },

    computed: {
      list () {
        return this.logistics || this.logisticOptions
      },
      currentShipment () {
        return {
          logistic_id: this.logistic_id,
          tracking_number: this.tracking_number
        }
      },
      currentLogisticName () {
        return _.get(_.find(this.list, ['id', this.logistic_id]), 'name', '-')
      }
    },

    async mounted () {
      // this.shipment.getValue = this.getValue
      this.initValue(this.shipment)

      Nova.$on(this.eventKey, shipment => {
        this.initValue(shipment)
      })
    },

    destroyed () {
      Nova.$off(this.eventKey)
    }
  }
</script>

<style>
    .el-select .el-input {
        min-width: 120px;
    }

    .input-with-select .el-input-group__prepend {
        background-color: #fff;
    }
</style>