<template>
    <div class="overflow-hidden overflow-x-auto relative">
        <table
                class="table  w-full"
                cellpadding="0"
                cellspacing="0">
            <thead>
            <th class="text-left" v-for="header in headers" :style="tdStyle(header)" :key="header.text">{{header.text}}</th>
            </thead>
            <tbody>
            <tr v-for="(item,index) in value" :key="index">
                <td v-for="attr in item.options" :key="attr.code"><span class="whitespace-no-wrap text-90 text-left font-normal">{{getOptionValueName(attr)}}</span>
                </td>

                <td><span class="whitespace-no-wrap text-left">
                <input v-if="!detail" class="w-full form-control form-input form-input-bordered"
                       id="code"
                       name="code"
                       type="text"
                       :value="item.code"
                       @input="onInputChange($event.target.value,item,index,'code')"
                       placeholder="请输入变体code"
                >
                    <p v-else>{{item.code}}</p>
                </span></td>

                <td><span class="whitespace-no-wrap text-left">
                <input v-if="!detail" class="w-full form-control form-input form-input-bordered"
                       id="price"
                       name="price"
                       type="number"
                       step="0.1"
                       :value="item.price"
                       @input="onInputChange($event.target.value,item,index,'price')"
                       placeholder="请输入变体参考价格"
                >
                    <p v-else>{{item.price}}</p>
                </span></td>

                <td><span class="whitespace-no-wrap text-left">
                <input v-if="!detail" class="w-full form-control form-input form-input-bordered"
                       id="width"
                       name="width"
                       type="text"
                       :value="item.width"
                       @input="onInputChange($event.target.value,item,index,'width')"
                       placeholder="请输入变体width"
                >
                    <p v-else>{{item.width}}</p>
                </span></td>

                <td><span class="whitespace-no-wrap text-left">
                <input v-if="!detail" class="w-full form-control form-input form-input-bordered"
                       id="height"
                       name="height"
                       type="text"
                       :value="item.height"
                       @input="onInputChange($event.target.value,item,index,'height')"
                       placeholder="请输入变体height"
                >
                    <p v-else>{{item.height}}</p>
                </span></td>

                <td><span class="whitespace-no-wrap text-left">
                <input v-if="!detail" class="w-full form-control form-input form-input-bordered"
                       id="length"
                       name="length"
                       type="text"
                       :value="item.length"
                       @input="onInputChange($event.target.value,item,index,'length')"
                       placeholder="请输入变体length"
                >
                    <p v-else>{{item.length}}</p>
                </span></td>

                <td><span class="whitespace-no-wrap text-left">
                <input v-if="!detail" class="w-full form-control form-input form-input-bordered"
                       id="weight"
                       name="weight"
                       type="text"
                       :value="item.weight"
                       @input="onInputChange($event.target.value,item,index,'weight')"
                       placeholder="请输入变体weight"
                >
                    <p v-else>{{item.weight}}</p>
                </span></td>

            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
  import Translation from '../translation'

  export default {
    name: 'sku-table',

    mixins: [Translation],

    model: {
      prop: 'value',
      event: 'input'
    },

    props: {
      value: {type: Array, default: () => []},
      options: {type: Array, default: () => []},
      detail: {type: Boolean, default: false},
      getOptionName: {
        type: Function
      }
    },

    methods: {
      onInputChange (value, item, index, field) {
        // if (field === 'price') { value = +value }
        const val = _.cloneDeep(this.value)
        val[index][field] = value
        this.$emit('input', val)
        this.$emit('cache-sku-item', {key: item.key, attr: val[index]})
      },
      tdStyle (td) {
        return td.style || {}
      }
    },
    computed: {
      headers () {
        let col = _.head(this.value)
        if (col) {
          return [...col.options.map(item => ({
            text: this.getOptionName(item),
            value: 'value_name'
          })),
            {text: 'Code', value: 'code'},
            {text: 'Price', value: 'price',style: {width: '8.5rem'}},
            {text: 'Width', value: 'width', style: {width: '5.5rem'}},
            {text: 'Height', value: 'height', style: {width: '5.5rem'}},
            {text: 'Length', value: 'length', style: {width: '5.5rem'}},
            {text: 'Weight', value: 'weight', style: {width: '5.5rem'}},
          ]
        }
        return []
      }
    }
  }
</script>

<style scoped>
    .number-td {
        min-width: 2.5rem;
    }
</style>