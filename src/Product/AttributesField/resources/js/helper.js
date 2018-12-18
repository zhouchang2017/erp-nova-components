export default {
  data () {
    return {
      attributes: []
    }
  },
  methods: {


    fillDefaultValue (item) {
      if (_.has(this.value, [item.id])) {
        return Object.keys(this.locales).reduce((res, locale) => {
          res.value[locale] = _.get(this.value, `${item.id}.${locale}.text_value`, null)
          res.origin[locale] = _.get(this.value, `${item.id}.${locale}.id`, null)
          return res
        }, {value: {}, origin: {}})
      }
      return {value: {}, origin: {}}
    },

    fillAttributesValues(data){
      this.attributes = data.map(item => {
        item.attribute = this.field.attribute
        item = Object.assign({}, item, this.fillDefaultValue(item))
        return item
      })
    }
  }

}