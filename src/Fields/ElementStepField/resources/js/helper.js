export default {
  data () {
    return {
      value: 0
    }
  },
  computed: {
    actived () {
      return this.field.options.findIndex(item => item.value === this.value)
    },

    failOption () {
      return _.get(this, 'field.failOption', {title: '已取消', description: '该项目已取消', value: 'CANCEL'})
    },

    isFail () {
      return this.value === this.failOption.value
    }
  },

  mounted () {
    this.value = this.field.value
  }
}