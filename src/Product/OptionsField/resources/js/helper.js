export default {
  methods: {
    /*
      * 获取默认option value
      * */
    getDefaultOptionValues (id) {
      return _.get(this, `field.optionValues.${id}`, [])
    },

    isCustom (option) {
      return _.isNull(option.selected)
    },

    filterOptionValues () {
      const optionValues = this.field.optionValues
      for (let id in optionValues) {
        const temp = []
        optionValues[id] = optionValues[id].reduce((res, value) => {
          if (!this.isCustom(value)) {
            // selected
            res.push(value)
          } else {
            if (!temp.find(arr => arr.value === value.value)) {
              temp.push(value)
              res.push({...value, origins: [value]})
            } else {
              const already = res.find(val => val.value === value.value)
              already.origins.push(value)
            }
          }
          return res
        }, [])
      }
      return optionValues
    },
  }
}