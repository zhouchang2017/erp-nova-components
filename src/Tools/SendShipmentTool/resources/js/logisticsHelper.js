export default {
  props: {
    logistics: {
      type: Array,
      default: () => []
    },
  },
  data () {
    return {
      logisticOptions: [],
    }
  },
  methods: {
    getLogisticsList () {
      return Nova.request().get(this.logisticsApi)
        .then(({data}) => {
          return data
        })
        .catch(err => {
          console.error(err.response)
        })
    },
    async initLogistics(){
      if (this.logistics.length <= 0) {
        this.logisticOptions = await this.getLogisticsList()
      }
    }
  },
  computed: {
    logisticsApi () {
      return `${Nova.config['erp-prefix']}/logistics`
    },
  }
}