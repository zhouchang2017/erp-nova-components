<template>
    <div v-if="canShow && !isSubmit"  class="flex items-center justify-center">
        <button
                class="my-3 flex-no-shrink ml-auto w-full btn btn-default btn-primary inline-flex justify-center items-center"
                @click.prevent="submit"
                v-loading.fullscreen.lock="loading">
            <svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-4 h-4 mr-1" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"/></svg>

            <span>确认入库</span>
        </button>
    </div>
</template>

<script>
  import slotHelper from '../slotHelper'

  export default {
    name: 'to-put',
    mixins: [slotHelper],
    data () {
      return {
        loading: false,
        isSubmit:false
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
          this.isSubmit = true
          // this.resource.authorizedToUpdate = false
        } catch (e) {
          console.error(e.response)
        }
      }
    },

    computed: {
      api () {
        return `${Nova.config['erp-prefix']}/${this.resourceName}/${this.resourceId}/put`
      }
    }
  }
</script>

<style scoped>

</style>