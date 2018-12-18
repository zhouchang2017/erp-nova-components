<template>
    <div class="flex items-center">
        <div class="w-1/5 py-3">
            <span
                    class="inline-block bg-primary-30% rounded-sm px-3 py-1 text-80 text-sm">
                {{field.name}}
            </span>

        </div>
        <div class="w-1/2 py-3 px-8">
            <div class="flex">
                <translation-detail-option-value :class="{'mr-3':field.values.length>index+1}"
                                                 v-for="(value,index) in field.values" :key="value.id" :value="value">

                </translation-detail-option-value>
            </div>
        </div>
    </div>
</template>

<script>
  import TranslationDetailOptionValue from './TranslationDetailOptionValue'

  export default {
    name: 'translation-detail-field',
    props: ['field'],
    components: {
      TranslationDetailOptionValue
    }
  }
</script>

<style scoped>

</style>