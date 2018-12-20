<template>
    <span>{{ actived.title }}</span>
</template>

<script>
  export default {
    props: ['resourceName', 'field'],
    computed: {
      actived () {
        if(this.field.value === this.failOption.value){
          return this.failOption
        }
        return this.field.options.find(item => item.value === this.field.value)
      },
      failOption () {
        return _.get(this, 'field.failOption', {title: '已取消', description: '该项目已取消', value: 'CANCEL'})
      }
    }
  }
</script>
