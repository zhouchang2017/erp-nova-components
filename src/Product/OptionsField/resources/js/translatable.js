export default {
  computed: {
    locales () {
      return _.get(Nova, 'config.locales')
    },
    indexLocale () {
      return _.get(Nova, 'config.indexLocale')
    }
  }
}