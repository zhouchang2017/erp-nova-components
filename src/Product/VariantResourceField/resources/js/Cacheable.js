export default {
  data () {
    return {
      cacheable: true,
      cachedVariants: new Map(),
    }
  },
  methods: {

    // 获取缓存数据
    getCachedVariant (key) {
      return this.cachedVariants.has(key) ? this.cachedVariants.get(key) : false
    },

    // 设置缓存数据
    setCachedVariant ({key, variants}) {
      this.cachedVariants.set(key, variants)
    },
  },
  computed: {
    getKey () {
      return this.field.relationResources + '-' + this.field.relationResourceTypeId + '-' + this.currentPage
    }
  }
}