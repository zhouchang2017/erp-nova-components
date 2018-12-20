<template>
    <div>
        <div class="flex items-center justify-center" v-if="isFail">
            <div class="appearance-none fill-danger px-3 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="35" height="35"><path class="heroicon-ui" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"/></svg>
            </div>
            <div class="px-3 py-3 text-80">
                <h1>{{failOption.title}}</h1>
            </div>
        </div>
        <el-steps v-else class="py-6" :active="actived" finish-status="success" process-status="process" align-center>
            <el-step
                    v-for="option in field.options"
                    :key="option.title"
                    :title="option.title"
                    :description="option.description"
            ></el-step>
        </el-steps>
    </div>

</template>

<script>
  import { FormField, HandlesValidationErrors } from 'laravel-nova'
  import Helper from '../helper'

  export default {
    mixins: [FormField, HandlesValidationErrors, Helper],

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue () {
        this.value = this.field.value || this.defaultValue
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill (formData) {
        // formData.append(this.field.attribute, this.value || 0)
      },

      /**
       * Update the field's internal value.
       */
      handleChange (value) {
        this.value = value
      },
    },
    computed: {
      defaultValue () {
        const options = _.get(this, 'field.options', [])
        return _.get(_.head(options), 'value', '')
      }
    }
  }
</script>
