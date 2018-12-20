<template>
    <div
            class="relative"
            :class="{'overflow-hidden' : loading}"
    >
        <div v-if="loading" class="flex items-center justify-center z-50 p-6" style="min-height: 150px">
            <loader class="text-60"/>
        </div>
        <template v-else>
            <div v-if="!variants.length" class="flex justify-center items-center px-6 py-8">
                <div class="text-center">
                    <svg class="mb-3" xmlns="http://www.w3.org/2000/svg" width="65" height="51" viewBox="0 0 65 51">
                        <g id="Page-1" fill="none" fill-rule="evenodd">
                            <g id="05-blank-state" fill="#A8B9C5" fill-rule="nonzero" transform="translate(-779 -695)">
                                <path id="Combined-Shape"
                                      d="M835 735h2c.552285 0 1 .447715 1 1s-.447715 1-1 1h-2v2c0 .552285-.447715 1-1 1s-1-.447715-1-1v-2h-2c-.552285 0-1-.447715-1-1s.447715-1 1-1h2v-2c0-.552285.447715-1 1-1s1 .447715 1 1v2zm-5.364125-8H817v8h7.049375c.350333-3.528515 2.534789-6.517471 5.5865-8zm-5.5865 10H785c-3.313708 0-6-2.686292-6-6v-30c0-3.313708 2.686292-6 6-6h44c3.313708 0 6 2.686292 6 6v25.049375c5.053323.501725 9 4.765277 9 9.950625 0 5.522847-4.477153 10-10 10-5.185348 0-9.4489-3.946677-9.950625-9zM799 725h16v-8h-16v8zm0 2v8h16v-8h-16zm34-2v-8h-16v8h16zm-52 0h16v-8h-16v8zm0 2v4c0 2.209139 1.790861 4 4 4h12v-8h-16zm18-12h16v-8h-16v8zm34 0v-8h-16v8h16zm-52 0h16v-8h-16v8zm52-10v-4c0-2.209139-1.790861-4-4-4h-44c-2.209139 0-4 1.790861-4 4v4h52zm1 39c4.418278 0 8-3.581722 8-8s-3.581722-8-8-8-8 3.581722-8 8 3.581722 8 8 8z"/>
                            </g>
                        </g>
                    </svg>

                    <h3 class="text-base text-80 font-normal mb-6">
                        No Product Variant matched the given criteria
                    </h3>

                </div>
            </div>
            <div v-else class="overflow-hidden overflow-x-auto relative rounded">
                <table
                        class="table  w-full"
                        cellpadding="0"
                        cellspacing="0">
                    <thead>
                    <!-- Select Checkbox -->
                    <th class="w-16">&nbsp;</th>
                    <th class="text-left" v-for="header in headers" :key="header.text">{{header.text}}</th>
                    </thead>
                    <tbody>

                    <tr v-for="(item,index) in variants" :key="index">
                        <td class="w-16">
                            <checkbox
                                    :data-testid="`${index}-checkbox`"
                                    :checked="isChecked(item)"
                                    @input="toggleSelection(item)"
                            />
                        </td>
                        <td v-for="(header,ind) in headers" :key="ind">
                            <span v-if="header.text === 'Index'"
                                  class="whitespace-no-wrap text-left">{{index+1}}</span>
                            <span v-else-if="header.to" class="whitespace-no-wrap text-left">
                            <router-link
                                    target="_blank"
                                    :to="toRelation(item,header)"
                                    class="no-underline font-bold dim text-primary"
                            >
                                {{ getValue(item,header.value) }}
                            </router-link>
                        </span>
                            <span v-else class="whitespace-no-wrap text-left">{{getValue(item,header.value)}}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <slot>

        </slot>
    </div>
</template>

<script>
  export default {
    name: 'variant-table',

    inject: ['resourceName', 'resourceId', 'field', 'errors'],

    props: {
      loading: {
        default: true
      },
      variants: {
        type: Array,
        default: () => []
      },
      checked: {
        type: Array,
        default: () => []
      }
    },
    model: {
      prop: 'checked',
      event: 'input'
    },
    data () {
      return {
        cachedCancelChecked: new Map()
      }
    },
    methods: {
      // 获取缓存数据
      getCachedChecked (key) {
        return this.cachedCancelChecked.has(key) ? this.cachedCancelChecked.get(key) : false
      },
      // 设置缓存数据
      setCachedChecked ({key, item}) {
        this.cachedCancelChecked.set(key, item)
      },
      getValue (item, key) {
        return _.get(item, key)
      },
      // 返回资源链接路由
      toRelation (item, header) {
        return {
          name: header.to.name, params: {
            resourceName: header.to.resourceName,
            resourceId: item.id
          }
        }
      },
      isChecked (item) {
        return this.checked.find(checked => checked.variant.id === item.id)
      },
      toggleSelection (item) {
        console.log(item)

        const index = this.checked.findIndex(checked => checked.variant.id === item.id)
        if (index >= 0) {
          this.setCachedChecked({key: item.id, item: this.checked.splice(index, 1)[0]})
        } else {
          const cached = this.getCachedChecked(item.id)
          if (cached) {
            this.checked.push(cached)
          } else {
            this.checked.push({
              id: null,
              name: item.name,
              pcs: '0',
              price: '0.00',
              helpText: `最大数量不能超过${item.stock}`,
              product_id: item.product_id,
              product_variant_id: item.id,
              variant: item,
            })
          }
        }
        this.$emit('input', this.checked)
      }
    },
    computed: {
      headers () {
        return [
          {text: 'Index', value: 'id'},
          {text: 'Name', value: 'variantName', to: {name: 'detail', resourceName: 'product-variants'}},
          {text: 'Code', value: 'code'},
          {text: 'Stock', value: 'stock'},
        ]
      },
      eventName(){
        return _.get(this,'field.attribute','selected')
      }
    },
    mounted () {
      Nova.$on(`${this.eventName}-cache-product-variant-table-item`, item => {
        this.setCachedChecked({key: item.product_variant_id, variants: item})
      })
    },
    beforeDestroy () {
      Nova.$off(`${this.eventName}-cache-product-variant-table-item`)
    }
  }
</script>

<style scoped>

</style>