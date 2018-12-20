export default {
  data () {
    return {
      page: 1,
      perPage: 15
    }
  },
  methods: {
    previousPage () {
      this.page = this.page - 1
    },
    nextPage () {
      this.page = this.page + 1
    }
  }
}