<template>
    <button v-if="uncommit"
            @click.prevent="submit"
            v-loading.fullscreen.lock="loading"
            class="flex-no-shrink ml-auto w-full btn btn-default btn-primary inline-flex justify-center items-center">
        <svg class="fill-current w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
             height="24">
            <path class="heroicon-ui"
                  d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/>
        </svg>
        <span>提交审核</span>
    </button>
</template>

<script>
  import SlotMixins from '../slotMixins'

  export default {
    name: 'to-review',
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
          this.resource.authorizedToUpdate = false
        } catch (e) {
          console.error(e.response)
        }
      }
    },

    computed: {
      api () {
        return `${Nova.config['erp-prefix']}/${this.resourceName}/${this.resourceId}/review`
      },
      uncommit () {
        return this.value === this.field.canToReviewValue
      }
    }
  }
</script>

<style scoped>

</style>