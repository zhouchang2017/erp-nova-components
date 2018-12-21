<template>
    <div v-if="uncommit"  class="flex items-center justify-center">
        <el-button type="danger" class="mx-3">取消申请</el-button>
        <el-button class="mx-3" >拒绝通过</el-button>
        <el-button class="mx-3" type="primary" @click="submit" v-loading.fullscreen.lock="loading">通过审核</el-button>
    </div>
</template>

<script>
  import SlotMixins from '../slotMixins'
  export default {
    name: 'to-approved',

    mixins: [SlotMixins],

    data () {
      return {
        loading: false,
      }
    },

    methods: {
      async submit () {
        this.loading = true
        try {
          let {data} = await Nova.request().put(this.api)
          const {status, ...notify} = data
          this.changeStatus(status)
          this.$notify(notify)
          this.loading = false
          // this.resource.authorizedToUpdate = false
        } catch (e) {
          console.error(e.response)
        }
      }
    },

    computed: {
      api () {
        return `${Nova.config['erp-prefix']}/${this.resourceName}/${this.resourceId}/approved`
      },
      uncommit () {
        return this.value === this.field.canStatus
      }
    }
  }
</script>

<style scoped>

</style>