<template>
    <div>
        <div v-if="!selected.length" class="flex justify-center items-center px-6 py-8">
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
                    暂无选中任何产品
                </h3>

            </div>
        </div>
        <div v-else class="overflow-hidden overflow-x-auto relative rounded">
            <table
                    class="table w-full"
                    cellpadding="0"
                    cellspacing="0">
                <thead>

                <th class="text-left" v-for="header in headers" :key="header.text">{{header.text}}</th>


                <th v-if="!detail"><!-- Edit,Delete --></th>
                </thead>
                <tbody>

                <tr
                        v-for="(resource, index) in selected"
                        :key="index"
                        is="selected-table-row"
                        :resource="resource"
                        :index="index"
                        :headers="headers"
                        :detail="detail"
                />
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

  import SelectedTableRow from './SelectedTableRow'

  export default {
    name: 'SelectedTable',
    components: {
      SelectedTableRow
    },
    props: {
      selected: {
        type: Array,
        default: () => []
      },
      detail: {
        type: Boolean,
        default: false
      }
    },

    computed: {
      headers () {
        return [
          {text: 'Index', value: 'id', edit: false},
          {text: 'Name', value: 'variant.variantName', to: {name: 'detail', resourceName: 'product-variants'}, edit: false},
          {text: 'Code', value: 'variant.code', edit: false},
          {text: 'Pcs', value: 'pcs', edit: true, component: 'element-number'},
        ]
      }
    },
  }
</script>

<style scoped>

</style>