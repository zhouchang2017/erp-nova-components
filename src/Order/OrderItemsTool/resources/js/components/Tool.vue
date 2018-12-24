<template>
    <el-table
            :data="items"
            style="width: 100%">
        <el-table-column
                prop="variant.variantName"
                label="商品名称"
                >
        </el-table-column>
        <el-table-column
                prop="quantity"
                label="数量"
                >
        </el-table-column>

        <el-table-column
                label="出货仓库"
        >
            <template slot-scope="scope">
                <el-select  placeholder="请选择出货仓库">
                    <el-option
                            v-for="item in assignments[scope.row.variant.id]"
                            :key="item.id"
                            :label="item.warehouse.name"
                            :value="item.warehouse_id">
                        <span style="float: left">{{ item.warehouse.name }}</span>
                        <span style="float: right; color: #8492a6; font-size: 13px">{{ item.stock }}</span>
                    </el-option>
                </el-select>
            </template>
        </el-table-column>
    </el-table>
</template>

<script>
  export default {
    props: ['resourceName', 'resourceId', 'field'],
    data () {
      return {
        resource: {},
        assignments: {}
      }
    },
    methods: {
      fetchResource () {
        Nova.request().get(this.api)
          .then(({data}) => {
            this.resource = data
          })
      },
      assignmentWarehouse () {
        Nova.request().get(this.assignmentWarehouseApi)
          .then(({data}) => {
            this.assignments = data
          })
      }
    },

    computed: {
      api () {
        return `${Nova.config['erp-prefix']}/${this.resourceName}/${this.resourceId}`
      },
      assignmentWarehouseApi () {
        return this.api + '/assignment'
      },
      items(){
        return _.get(this,'resource.orderable.items',[])
      }
    },

    async mounted () {
      await Promise.all([
        this.fetchResource(),
        this.assignmentWarehouse()
      ])
    }
  }
</script>
