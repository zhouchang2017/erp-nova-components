<template>
    <div>
        <div class="px-8 py-6 border-b border-40">
            <p class="text-80 pt-2 py-2 leading-tight">Product Variants</p>
            <div class="relative h-9 mb-6 flex-no-shrink">
                <icon type="search" class="absolute search-icon-center ml-3 text-70"/>

                <input
                        data-testid="search-input"
                        dusk="search"
                        class="form-input-bordered form-control form-input w-search pl-search"
                        :placeholder="__('Search')"
                        type="search"
                        v-model="search"
                        @keydown.stop="performSearch"
                        @search="performSearch"
                >
            </div>
            <variant-table v-model="value" :variants="variants.data" :loading="loading">
                <!-- Pagination -->
                <pagination-links
                        v-if="hasResources"
                        :resource-name="resourceName"
                        :resources="variants.data"
                        :resource-response="variants"
                        @previous="previousPage"
                        @next="nextPage">
                </pagination-links>
            </variant-table>
        </div>
        <div class="py-6 px-8 border-b border-40">
            <p class="text-80 pt-2 py-2 leading-tight">Selected Product Variants</p>
            <div v-if="hasError" class="my-3">
                <el-alert
                        :closable="false"
                        :title="firstError"
                        type="error">
                </el-alert>
            </div>

            <selected-table :selected="value"/>
        </div>
    </div>
    <!--<default-field :field="field" :errors="errors">-->
    <!--<template slot="field">-->
    <!--<input :id="field.name" type="text"-->
    <!--class="w-full form-control form-input form-input-bordered"-->
    <!--:class="errorClasses"-->
    <!--:placeholder="field.name"-->
    <!--v-model="value"-->
    <!--/>-->
    <!--</template>-->
    <!--</default-field>-->
</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova'
  import Helper from '../helper'
  import VariantTable from './VariantTable'
  import Pageable from '../Paegable'
  import Searchable from '../Searchable'
  import SelectedTable from './SelectedTable'

  export default {
    mixins: [FormField, HandlesValidationErrors, Helper, Pageable, Searchable],

    props: ['resourceName', 'resourceId', 'field'],

    provide () {
      return {
        resourceName: this.resourceName,
        resourceId: this.resourceId,
        field: this.field,
        errors: this.errors
      }
    },

    components: {
      VariantTable,
      SelectedTable
    },

    data () {
      return {
        variants: {data: []},
        loading: true,
        value: [],
        fillable: ['id', 'inventory_income_id', 'inventory_expend_id', 'pcs', 'price', 'product_variant_id', 'product_id']
      }
    },

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue () {
        this.value = this.field.value || ''
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill (formData) {
        // formData.append(this.field.attribute, this.value || '')
        this.value.forEach((item, index) => {
          _.each(_.pick(item, this.fillable), (value, key) => {
            formData.append(`${this.field.attribute}[${index}][${key}]`, value || '')
          })
        })
      },

      /**
       * Update the field's internal value.
       */
      handleChange (value) {
        this.value = value
      },

    },

    mounted () {
      Nova.$on(`${this.field.attribute}-edit-selected-resource`, ({index, pcs}) => {
        let selected = this.value[index]
        _.set(selected, 'pcs', pcs)
      })

      Nova.$on(`${this.field.attribute}-delete-selected-resource`, item => {
        const index = this.value.findIndex(selected => selected === item)
        this.value.splice(index, 1)
      })
    },

    destroyed () {
      Nova.$off(`${this.field.attribute}-edit-selected-resource`)
      Nova.$off(`${this.field.attribute}-delete-selected-resource`)
    },

    async created () {
      this.$watch(
        () => {
          return (
            this.relationResource +
            this.relationResourceId +
            this.resourceName +
            this.currentSearch +
            this.currentPage +
            this.currentPerPage
          )
        },
        async () => {
          if (this.relationResource && this.relationResourceId) {
            await this.setVariants()
          } else {
            this.loading = false
          }
        },
        {immediate: true}
      )
    }
  }
</script>
