export default {
  data () {
    return {
      skuTableSchema: [],
      optionNames: {},
      skuItemAttr: new Map(),
    }
  },
  methods: {
    setVariantKey (value) {
      const clone = _.isArray(value) ? value : []
      return clone.map(variant => {
        variant.key = variant.option_values.map(value => value.unique_code).join('-')

        const {id, code, price: {price}, height, length, width, weight} = variant
        this.setSkuItemAttr({key: variant.key, attr: {id, code, price, height, length, width, weight}})

        return variant
      })
    },

    // 获取缓存数据
    getSkuItemAttr (key) {
      return this.skuItemAttr.has(key) ? this.skuItemAttr.get(key) : false
    },

    // 设置缓存数据
    setSkuItemAttr ({key, attr}) {
      this.skuItemAttr.set(key, attr)
    },

    // 通过销售所选销售属性生成变体组合
    genCartesian (elements) {
      // const formData = Object.values(_.groupBy(this.checked,'option_id'))
      if (!Array.isArray(elements)) {
        throw new TypeError()
      }

      let end = elements.length - 1,
        result = []

      function addTo (curr, start) {
        let first = elements[start],
          last = (start === end)

        for (let i = 0; i < first.length; ++i) {
          let copy = curr.slice()
          copy.push(first[i])

          if (last) {
            result.push(copy)
          } else {
            addTo(copy, start + 1)
          }
        }
      }

      if (elements.length) {
        addTo([], 0)
      } else {
        result.push([])
      }
      return result
    },

    setOptionNames () {
      this.optionNames = this.options.reduce((res, option) => {
        option.translations.forEach(translation => {
          _.set(res, `${option.id}.${translation.locale_code}`, translation.name)
        })
        return res
      }, {})
    },
    getOptionName (column) {
      return _.get(this.optionNames, `${column.option_id}.${this.indexLocale}`, '-')
    },
    setSkuTableSchema () {
      if (!this.canGenSku) {
        this.skuTableSchema = []
        return
      }
      this.skuTableSchema = this.genCartesian(this.checked).map(item => {

        const key = item.map(value => value.unique_code).join('-')
        // const primaryKey
        let cache = this.getSkuItemAttr(key)

        if (!cache) {
          cache = {
            id: null,
            code: '',
            price: '0.00',
            height: 0,
            length: 0,
            width: 0,
            weight: 0,
          }
          this.setSkuItemAttr({key, attr: cache})
          // reset options =>id
        }
        if (_.isNull(cache.id)) {
          item = item.map(opt => {
            const newOpt = _.cloneDeep(opt)
            newOpt.id = null
            newOpt.variant_id = null

            if (newOpt.hasOwnProperty('origins')) {
              delete newOpt.origins
            }
            return newOpt
          })
        }
        if (cache.id) {
          item = item.map(opt => {
            let origins = _.get(opt, 'origins', [])
            let target = _.find(origins, ['variant_id', cache.id])
            const newOpt = _.cloneDeep(opt)
            delete newOpt.origins
            if (target) {
              newOpt.id = target.id
              newOpt.variant_id = target.variant_id
            }

            return newOpt
          })
        }
        return {options: item, ...cache, key}
      })
    }

  }
}