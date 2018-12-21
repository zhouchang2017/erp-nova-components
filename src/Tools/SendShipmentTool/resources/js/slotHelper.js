export default {
  props: ['resourceName', 'resourceId', 'field', 'resource'],

  methods: {
    changeStatus (status) {
      Nova.$emit(this.statusEvent, status)
    }
  },

  computed: {
    statusEvent () {
      return _.get(this, 'field.statusField', 'status') + '-change'
    },
    // 是否可见
    canShow () {
      return _.get(this.field, 'canShow', 'SHIPPED') === _.get(this, 'resource.status')
    },
  }
}