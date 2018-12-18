<template>
    <el-tooltip placement="top">
        <ul slot="content">
            <li v-for="value in localeValues">{{value.value}}</li>
        </ul>
        <p class="text-90">
            {{currentValue}}
        </p>
    </el-tooltip>
</template>

<script>
  export default {
    name: 'TranslationDetailOptionValue',
    props: ['value'],

    methods: {
      getOptionValue (localeCode) {
        return _.get(_.find(this.realTranslations, ['locale_code', localeCode]), 'value', '-')
      }
    },

    computed: {
      isCustom () {
        return _.isNull(this.value.selected)
      },

      currentValue () {
        return this.getOptionValue(this.indexLocale)
      },

      localeValues () {
        return _.map(this.locales, (value, key) => {
          return {
            locale: value,
            value: this.getOptionValue(key)
          }
        })
      },

      realTranslations () {
        return this.isCustom ? this.value.translations : this.value.selected.translations
      }
    }
  }
</script>

<style scoped>

</style>