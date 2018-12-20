<template>
    <card class="overflow-hidden border border-50 mb-6 my-3">

        <el-alert
            title="可以为单个SKU最小单位拆分物流"
            type="info"
            close-text="知道了">
        </el-alert>


        <el-table
                :data="units"
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
                                        :disabled="!!row.shipment_track_id"
                    ></track-number-input>
                </template>
            </el-table-column>
            <el-table-column
                    label="操作"
                    align="right"
                    min-width="50">
                <template slot-scope="{$index,row}">
                    <span v-if="row.shipment_track_id">已发货</span>
                    <el-button v-else type="primary" size="small">发货</el-button>
                </template>
            </el-table-column>
        </el-table>
    </card>
    <!--<div v-for="(unit,index) in props.row.units" :key="index"-->
         <!--:class="[props.row.units.length === (index +1) ? 'border-none' : 'border-b border-40']"-->
         <!--class="flex py-2 items-center">-->
            <!--<span class="w-1/6">-->
            <!--# {{index + 1}}-->
            <!--</span>-->
        <!--<track-number-input :disabled="!isSelected(props.row)" :event-key="`track-number-item-${props.row.id}-change`"-->
                            <!--:shipment="unit.shipment" :logistics="logistics"></track-number-input>-->
    <!--</div>-->
</template>

<script>
  export default {
    name: 'VariantListItemTable',
    props:{
      units:{
        type:Array,
        default:()=>[]
      }
    }
  }
</script>

<style scoped>

</style>