<template>
    <div class="flex items-center">
        <div class="w-1/5 py-6 px-8">
            <span
                    class="inline-block bg-30 rounded-sm px-3 py-1 text-80 text-sm">
                {{field.name}}
            </span>

        </div>
        <div class="w-1/2 py-6 px-8">
            <a
                    class="inline-block border-50 font-bold cursor-pointer mr-2 animate-text-color select-none"

                    v-for="(locale, localeKey) in localesMap"
                    :key="`a-${localeKey}`"
                    :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                    @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>

            <input
                    ref="field"
                    type="text"
                    :id="field.name"
                    class="mt-4 w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.name"
                    v-model.trim="value[currentLocale]"
                    @keydown.tab="handleTab"
            />

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </div>
    </div>
</template>

<script>


  import { FormField, HandlesValidationErrors } from 'laravel-nova'

  export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
      return {
        locales: [],
        currentLocale: null,
      }
    },

    mounted() {
      this.locales = Object.keys(this.localesMap)

      this.currentLocale = this.locales[0] || null;

      Nova.$on(`'localeChanged-'${this.field.name}`, locale => {
        if(this.currentLocale !== locale) {
          this.changeTab(locale, true);
        }
      });
    },

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue() {
        this.value = this.field.value || {}
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill(formData) {
        Object.keys(this.value).forEach(locale => {
          formData.append(this.field.attribute + '[' + locale + ']', this.value[locale] || '')
        })
      },

      /**
       * Update the field's internal value.
       */
      handleChange(value) {
        this.value[this.currentLocale] = value
      },

      changeTab(locale, dontEmit) {
        if(this.currentLocale !== locale){
          if(!dontEmit){
            Nova.$emit(`'localeChanged-'${this.field.name}`, locale);
          }

          this.currentLocale = locale;

          this.$nextTick(() => {
            if (this.field.trix) {
              this.$refs.field.update()
            } else {
              this.$refs.field.focus()
            }
          })
        }
      },

      handleTab(e) {
        const currentIndex = this.locales.indexOf(this.currentLocale)
        if (!e.shiftKey) {
          if (currentIndex < this.locales.length - 1) {
            e.preventDefault();
            this.changeTab(this.locales[currentIndex + 1]);
          }
        } else {
          if (currentIndex > 0) {
            e.preventDefault();
            this.changeTab(this.locales[currentIndex - 1]);
          }
        }
      }
    },
    computed: {
      localesMap () {
        return _.get(Nova, 'config.locales')
      },
      indexLocale () {
        return _.get(Nova, 'config.indexLocale')
      }
    }
  }
</script>
