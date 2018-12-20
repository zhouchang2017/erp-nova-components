export default{
  props: ['resource', 'resourceName', 'resourceId', 'field', 'value'],
  methods:{
    changeStatus (status) {
      Nova.$emit(`${this.field.attribute}-change`, status)
    }
  }
}