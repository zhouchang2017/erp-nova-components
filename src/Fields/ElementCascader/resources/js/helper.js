export default {
  computed: {
    name () {
      return _.get(this, 'field.value.name', '-')
    },
    id () {
      return _.get(this, 'field.value.id')
    },
    eventKey () {
      return _.get(this, 'field.eventKey', this.field.attribute + '-value-change')
    }
  }
}