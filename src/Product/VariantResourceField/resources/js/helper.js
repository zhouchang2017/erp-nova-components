import { Minimum } from 'laravel-nova'

export default {
  data () {
    return {
      relationResource: null,
      relationResourceId: null,
      componentInit: true
    }
  },
  methods: {
    fetchResources () {
      this.loading = true
      return Minimum(
        Nova.request()
          .get(`${Nova.config['erp-prefix']}/${this.relationResource}/${this.relationResourceId}/variants`, {
            params: this.resourceRequestQueryString
          })
      )
        .then(({data}) => {
          this.loading = false
          return data
        })
        .catch(error => {
          console.error(error.response)
        })
    },
    async setVariants () {
      if (this.cacheable) {
        if (!this.getCachedVariant(this.getKey)) {
          const data = await this.fetchResources()
          this.setCachedVariant({key: this.getKey, variants: data})
        }
        this.variants = this.getCachedVariant(this.getKey)
      } else {
        this.variants = await this.fetchResources()
      }
    },

    relationResourceOnChange () {
      if (this.field.relationResourceOnChange) {
        Nova.$on(this.field.relationResourceOnChange, val => {
          if (this.field.relationResourceOnChangeValue) {
            val = _.get(val, this.field.relationResourceOnChangeValue)
          }
          this.relationResource = val
          this.restore()
        })
      }
    },

    relationResourceIdOnChange () {
      if (this.field.relationResourceIdOnChange) {
        Nova.$on(this.field.relationResourceIdOnChange, val => {
          if (this.field.relationResourceIdOnChangeValue) {
            val = _.get(val, this.field.relationResourceIdOnChangeValue)
          }
          this.relationResourceId = val
          this.restore()
        })
      }
    },

    restore(){
      if(this.editPage && this.field.relationResource === this.relationResource && this.field.relationResourceId === this.relationResourceId){
        this.value = this.field.value
      }else{
        this.value = []
      }
    }
  },
  computed: {
    editPage(){
      return this.$route.name === 'edit'
    },
    /*
    * 存在资源数据
    */
    hasResources () {
      return this.variants.data.length > 0
    },
    /*
    * 请求参数
    */
    resourceRequestQueryString () {
      return {
        search: this.currentSearch,
        perPage: this.currentPerPage,
        page: this.currentPage,
      }
    },
    /**
     * Get the current search value from the query string.
     */
    currentSearch () {
      return this.search || ''
    },
    /**
     * Get the current page value from the query string.
     */
    currentPage () {
      return this.page || 1
    },
    /**
     * Get the current perPage value from the query string.
     */
    currentPerPage () {
      return this.perPage || 15
    }
  },
  created () {
    this.relationResource = this.field.relationResource
    this.relationResourceId = this.field.relationResourceId
  },
  mounted () {
    this.relationResourceOnChange()
    this.relationResourceIdOnChange()
  },
  destroyed () {
    if (this.field.relationResourceOnChange) {
      Nova.$off(this.field.relationResourceOnChange)
    }

    if (this.field.relationResourceIdOnChange) {
      Nova.$off(this.field.relationResourceIdOnChange)
    }
  }
}