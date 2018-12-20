<template>
    <tr>
        <td v-for="(header,ind) in headers" :key="ind">
                    <span v-if="header.text === 'Index'"
                          class="whitespace-no-wrap text-left">{{index+1}}</span>
            <span v-else-if="header.to" class="whitespace-no-wrap text-left">
                            <router-link
                                    target="_blank"
                                    :to="toRelation(resource,header)"
                                    class="no-underline font-bold dim text-primary"
                            >
                                {{ getValue(resource,header.value) }}
                            </router-link>
                        </span>
            <span v-else class="whitespace-no-wrap text-left">{{getValue(resource,header.value)}}</span>
        </td>
        <td class="td-fit text-right pr-6" v-if="!detail">
            <button
                    class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                    @click.prevent="openEditModal"
            >
                <icon type="edit"/>
            </button>

            <button
                    class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                    @click.prevent="openDeleteModal"
            >
                <icon/>
            </button>

            <el-dialog
                    :visible.sync="deleteModalOpen"
                    width="30%"
                    :title="__('Delete')"
            >
                <span>是否确认删除</span>
                <div slot="footer" class="ml-auto">
                    <button
                            type="button"
                            data-testid="cancel-button"
                            dusk="cancel-delete-button"
                            @click.prevent="closeDeleteModal"
                            class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">
                        {{__('Cancel')}}
                    </button>
                    <button
                            id="confirm-delete-button"
                            ref="confirmButton"
                            data-testid="confirm-button"
                            @click.prevent="confirmDelete"
                            class="btn btn-default btn-danger">确认
                    </button>
                </div>
            </el-dialog>
            <el-dialog
                    :visible.sync="editModalOpen"
                    width="50%"
                    :title="__('Edit')"
            >
                <div>
                    <div class="flex items-center border-b border-40">
                        <div class="w-1/5 py-3 px-8">
                            <span
                                    class="inline-block  px-3 py-1 text-80 text-sm">
                                Name
                            </span>
                        </div>
                        <div class="w-4/5 py-3 px-8 flex">
                            <span
                                    class="inline-block  px-3 py-1 text-80 text-sm">
                                {{resource.variant.variantName}}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center border-b border-40">
                        <div class="w-1/5 py-3 px-8">
                            <span
                                    class="inline-block  px-3 py-1 text-80 text-sm">
                                Code
                            </span>
                        </div>
                        <div class="w-4/5 py-3 px-8 flex">
                            <span
                                    class="inline-block  px-3 py-1 text-80 text-sm">
                                {{resource.variant.code}}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-1/5 py-3 px-8">
                            <span
                                    class="inline-block  px-3 py-1 text-80 text-sm">
                                Pcs
                            </span>
                        </div>
                        <div class="w-4/5 py-3 px-8 flex">
                            <el-input-number

                                    v-model="pcs"
                                    :min="1" :max="isExpend ? resource.variant.stock : 'Infinity'" label="数量">

                            </el-input-number>
                        </div>
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

        </td>
    </tr>
</template>

<script>

  export default {
    name: 'selected-table-row',

    props: {
      resource: {
        type: Object,
        required: true
      },
      headers: {required: true},
      index: {default: 0},
      detail: {
        type: Boolean,
        default: false
      }
    },

    inject: {
      resourceName: {default: null},
      resourceId: {default: null},
      field: {default: null},
      errors: {default: null},
    },
    data () {
      return {
        deleteModalOpen: false,
        editModalOpen: false,
        editResourceFields: [],
        fill: null,
        pcs: '0'
      }
    },
    methods: {
      getValue (item, key) {
        return _.get(item, key)
      },
      // 返回资源链接路由
      toRelation (item, header) {
        return {
          name: header.to.name, params: {
            resourceName: header.to.resourceName,
            resourceId: item.variant.id
          }
        }
      },
      // 打开删除面板
      openDeleteModal () {
        this.deleteModalOpen = true
      },
      // 删除面板确认
      confirmDelete () {
        this.closeDeleteModal()
        Nova.$emit(`${this.eventName}-delete-selected-resource`, this.resource)
      },
      // 关闭删除面板
      closeDeleteModal () {
        this.deleteModalOpen = false
      },
      // 打开编辑面板
      openEditModal () {
        this.pcs = this.resource.pcs
        this.editModalOpen = true
      },
      // 编辑面板确认
      confirmEdit () {
        // emit

        Nova.$emit(`${this.eventName}-edit-selected-resource`, {
          pcs: this.pcs,
          index: this.index
        })
        this.closeEditModal()
      },
      // 关闭编辑面板
      closeEditModal () {
        this.editModalOpen = false
      }
    },

    computed: {
      isExpend () {
        return this.resourceName === 'inventory-expends'
      },
      eventName () {
        return _.get(this, 'field.attribute', 'selected')
      }
    }
  }
</script>

<style scoped>

</style>