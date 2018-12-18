export default {
  methods: {
    getOptionValueName (optionValue) {
      const isCustom = _.isNull(optionValue.selected)
      if (isCustom) {
        return _.find(optionValue.translations, ['locale_code', this.indexLocale]).value
      }
      const currentLang = _.find(optionValue.selected.translations, ['locale_code', this.indexLocale])
      return _.get(currentLang, 'value',
        _.head(optionValue.selected.translations).value
      )
    }
  }
}