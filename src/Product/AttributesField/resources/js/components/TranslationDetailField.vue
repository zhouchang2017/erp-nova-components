<template>
    <div class="flex items-center">
        <div class="w-1/5 py-3 ">
            <span
                    class="inline-block bg-30 rounded-sm px-3 py-1 text-80 text-sm">
                {{field.name}}
            </span>

        </div>
        <div class="w-1/2 py-3 px-8">
            <a
                    class="inline-block border-50 font-bold cursor-pointer mr-2 animate-text-color select-none"

                    v-for="(locale, localeKey) in locales"
                    :key="`a-${localeKey}`"
                    :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                    @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>
            <div class="mt-4">
                <span v-if="field.value[currentLocale]" v-html="field.value[currentLocale]"></span>
                <span v-else>—</span>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data () {
      return {
        languages:[],
        currentLocale: null,
      }
    },

    methods: {
      changeTab (locale) {
        this.currentLocale = locale
      }
    },
    mounted () {
      this.languages = Object.keys(this.locales)

      this.currentLocale = this.languages[0] || null

    },
  }
</script>
