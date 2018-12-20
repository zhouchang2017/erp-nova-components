export default {
  data () {
    return {
      search: ''
    }
  },
  methods: {
    /**
     * Execute a search against the resource.
     */
    performSearch (event) {
      this.debouncer(() => {
        // Only search if we're not tabbing into the field
        if (event.which != 9) {
          this.page = 1
          this.search = event.target.value
        }
      })
    },

    debouncer: _.debounce(callback => callback(), 500),
  }
}