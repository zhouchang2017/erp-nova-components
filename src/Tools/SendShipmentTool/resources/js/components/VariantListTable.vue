<template>
    <div>
        <el-table
                :data="items"
                @selection-change="handleSelectionChange"
                ref="variantTable"
                row-key="id"
                style="width: 100%">
            <el-table-column
                    type="selection"
                    label="ID"
                    prop="id">
            </el-table-column>
            <el-table-column type="expand">
                <template slot-scope="props">
                    <el-alert
                            v-if="!detail"
                            title="可以为单个SKU最小单位拆分物流"
                            type="info"
                            close-text="知道了">
                    </el-alert>
                    <card class="overflow-hidden border border-50 mb-6 my-3">

                        <el-table
                                :data="props.row.units"
                                style="width: 100%">
                            <el-table-column
                                    type="index"
                                    width="50">
                            </el-table-column>

                            <el-table-column
                                    label="物流单号"
                                    min-width="150">
                                <template slot-scope="{$index,row}">
                                    <track-number-input :event-key="`track-number-item-${row.id}-change`"
                                                        :shipment="row.shipment"
                                                        :logistics="logisticOptions"
                                                        :disabled="!!row.shipment_track_id || detail"
                                    ></track-number-input>
                                </template>
                            </el-table-column>
                            <el-table-column
                                    label="操作"
                                    align="right"
                                    min-width="50">
                                <template slot-scope="{$index,row}">
                                    <span v-if="row.shipment_track_id || detail">
                                        <button
                                                class="appearance-none focus:outline-none cursor-pointer text-70 hover:text-primary"
                                                @click.prevent="openTrackNumberDetailModal"
                                        >
                                            <icon type="view" width="22" height="18" view-box="0 0 22 16"/>
                                        </button>
                                    </span>
                                    <el-button v-else type="primary" size="small" @click="unitShipment(props.row,row)">
                                        发货
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </card>
                </template>
            </el-table-column>
            <el-table-column
                    label="商品名称"
                    prop="variant.variantName">
            </el-table-column>
            <el-table-column
                    label="数量"
                    min-width="60"
                    prop="pcs">
            </el-table-column>
            <el-table-column
                    v-if="!detail"
                    label="已发货"
                    min-width="60"
            >
                <template slot-scope="{$index,row}">
                    {{shippedCount(row)}}
                </template>
            </el-table-column>

            <el-table-column
                    v-if="!detail"
                    label="已填单号"
                    min-width="60"
            >
                <template slot-scope="{$index,row}">
                    {{shipmentFillCount(row)}}
                </template>
            </el-table-column>

            <el-table-column
                    v-if="!detail"
                    label="一键填充"
                    min-width="150">
                <template slot-scope="{$index,row}">
                    <track-number-input
                            v-if="!itemIsShipped(row)"
                            v-model="shipments[row.id]"

                            :logistics="logisticOptions">
                        <template slot-scope="{shipment,disabled}">
                            <button
                                    :disabled="disabled"
                                    class="appearance-none focus:outline-none cursor-pointer text-70 hover:text-primary"
                                    @click.prevent="fillSub(shipment,row)"
                            >
                                <icon type="edit"/>
                            </button>
                        </template>
                    </track-number-input>
                    <span v-else>该项目已完成发货</span>
                </template>
            </el-table-column>

            <el-table-column
                    v-if="!detail"
                    label="操作"
                    align="right"
                    min-width="50">
                <template slot-scope="{$index,row}">
                    <!--<button-->

                    <!--class="ml-3 flex-no-shrink ml-auto w-full btn btn-default btn-primary inline-flex justify-center items-center">-->
                    <!--<span>批量发货</span>-->
                    <!--</button>-->
                    <el-button :disabled="itemIsShipped(row)" type="primary" size="medium"
                               @click="$emit('shipment',[row])">批量发货
                    </el-button>
                    <!--<button-->
                    <!--class="appearance-none cursor-pointer focus:outline-none text-70 hover:text-primary"-->
                    <!--@click.prevent="dialogVisible = true"-->
                    <!--&gt;-->
                    <!--<icon type="edit"/>-->
                    <!--</button>-->
                </template>
            </el-table-column>

        </el-table>

        <div class="bg-30 flex py-4 px-6" v-if="!detail">
            <template v-if="!allShipped">
                <track-number-input :logistics="logisticOptions" style="width: 50%" class="ml-auto mr-3"
                >
                    <template slot-scope="{shipment,disabled}">
                        <button
                                class="appearance-none cursor-pointer text-70 hover:text-primary"
                                @click.prevent="fill(shipment)"
                        >
                            <icon type="edit"/>
                        </button>
                    </template>

                </track-number-input>

                <el-button type="primary" @click="$emit('shipment',items)">批量发货</el-button>
            </template>
            <span v-else class="ml-auto text-80 font-normal">发货已完成！</span>
        </div>
    </div>
</template>

<script>
  import TrackNumberInput from './TrackNumberInput'
  import logisticsHelper from '../logisticsHelper'

  export default {
    name: 'variant-list-table',
    mixins: [logisticsHelper],
    props: {
      items: {
        type: Array,
        default: () => []
      },
      detail: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return {
        shipments: {},
        multipleSelection: [],
        dialogVisible: false
      }
    },

    watch:{
      allShipped:function(value){
        if(value){
          this.$emit('all-shipped-change')
        }
      }
    },
    methods: {
      // 最小单位发货事件
      unitShipment (item, unit) {
        const clone = _.cloneDeep(item)

        clone.units = item.units.filter(u => u === unit)
        // console.log(clone)
        this.$emit('shipment', [clone])
      },
      // 检测填单信息
      checkShipment (shipment) {
        return new Promise((resolve, reject) => {
          if (shipment.logistic_id && shipment.tracking_number) {
            resolve()
          } else {
            reject('信息不完整，无法填充')
          }
        })

      },
      // 单个sku填充
      fillSub (shipment, item) {

        this.checkShipment(shipment).then(() => {
          item.units.forEach(unit => {
            if (_.isNull(unit.shipment_track_id)) {
              unit.shipment = Object.assign({}, unit.shipment, shipment)
              Nova.$emit(`track-number-item-${unit.id}-change`, shipment)
            }
          })
          this.$message({
            message: `${item.variant.variantName} 物流信息填充完成`,
            type: 'success'
          })
        }).catch(err => {
          this.$message({
            message: err,
            type: 'error'
          })
        })

      },

      // 全部填充
      fill (shipment) {
        this.checkShipment(shipment).then(() => {
          if (this.multipleSelection.length === 0) {
            this.$message({message: '请勾选变体', type: 'error'})
            return
          }
          this.multipleSelection.forEach(selected => {
            this.fillSub(shipment, selected)
          })
        }).catch(err => {
          this.$message({
            message: err,
            type: 'error'
          })
        })

      },

      // 初始化
      initShipments () {
        this.shipments = {}
        this.items.forEach(item => {
          this.$set(this.shipments, item.id, {logistic_id: null, tracking_number: ''})
        })
      },

      // 勾选事件
      handleSelectionChange (val) {
        this.multipleSelection = val
      },


      isSelected (row) {
        return this.multipleSelection.find(item => item.id === row.id)
      },

      // 已发货数量
      shippedCount (row) {
        return row.units.filter(unit => !_.isNull(unit.shipment_track_id)).length
      },

      // 以填单号数量
      shipmentFillCount (row) {
        return row.units.filter(unit => !_.isEmpty(unit.shipment.tracking_number)).length
      },

      // 查看物流
      openTrackNumberDetailModal () {

      }
    },

    components: {
      TrackNumberInput
    },

    computed: {
      // 是否全部发货
      allShipped () {
        return _.every(this.items.reduce((res, item) => {
          res.push(...item.units)
          return res
        }, []), unit => {
          return !_.isNull(unit.shipment_track_id)
        })
      },

      // 单sku发货状态检测
      itemIsShipped () {
        return (item) => {
          return _.every(item.units, unit => !_.isNull(unit.shipment_track_id))
        }
      }
    },

    async mounted () {
      await this.initLogistics()
      this.initShipments()
    }
  }
</script>

<style scoped>

</style>