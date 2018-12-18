<template>
    <el-dialog
            @open="open"
            :show-close="false"
            :visible.sync="editModalOpen"
            width="50%"
            :title="`为${item.name}添加多语言属性值`"
            @close="closeEditModal"
    >
        <div class="flex items-center locale-label" v-for="(locale,k) in locales" :key="locale">
            <div class="w-1/5 py-3 px-8">
            <span
                    class="inline-block bg-primary-30% rounded-sm px-3 py-1 text-80 text-sm">
                {{k}}
            </span>

            </div>
            <div class="w-4/5 py-3 px-8 flex">
                <input
                        type="text"
                        placeholder="添加自定义属性值"
                        v-model.trim="value[k]"
                        class="w-full form-control form-input form-input-bordered"/>
            </div>
        </div>

        <div slot="footer" class="ml-auto">
            <button type="button"
                    data-testid="cancel-button"
                    dusk="cancel-delete-button"
                    @click.prevent="closeEditModal"
                    class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">
                {{__('Cancel')}}
            </button>
            <button
                    id="confirm-edit-button"
                    ref="confirmButton"
                    data-testid="confirm-button"
                    @click.prevent="confirmEdit"
                    class="btn btn-default btn-primary">确认
            </button>
        </div>
    </el-dialog>
</template>

<script>

  export default {
    name: 'add-option-value-dialog',
    inject: ['item'],
    props: {
      editModalOpen: {
        type: Boolean,
        default: false
      },
      defaultOption: {
        type: Object
      }
    },
    model: {
      event: 'input',
      prop: 'editModalOpen'
    },
    data () {
      return {
        value: {},
      }
    },
    methods: {
      open () {
        this.value = {}
        if (!_.isEmpty(this.defaultOption)) {
          const availableLocales = _.get(this, 'defaultOption.translations', [])
          const fillableLocales = _.keys(this.locales)
          availableLocales.forEach(locale => {
            if (fillableLocales.includes(locale.locale_code)) {
              this.value[locale.locale_code] = _.get(locale, 'value', '')
            }
          })
        }
      },
      // 打开编辑面板
      openEditModal () {
        this.$emit('input', true)
      },
      // 编辑面板确认
      confirmEdit () {
        const value = this.value
        const defaultOption = _.isEmpty(this.defaultOption) ? null : this.defaultOption
        this.$emit('confirm', {value, defaultOption})
        this.closeEditModal()
      },
      // 关闭编辑面板
      closeEditModal () {
        this.$emit('input', false)
      },
    }
  }
</script>

<style scoped>
    .locale-label {
        border-bottom-width: 1px;
        border-color: var(--40);
    }

    .locale-label:last-child {
        border: none;
    }
</style>