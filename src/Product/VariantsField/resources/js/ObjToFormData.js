export default {
  methods: {
    objectToFormData (obj, form, namespace) {
      let fd = form || new FormData()
      let formKey
      if (obj instanceof Array) {
        obj.forEach((item, index) => {
          if (typeof item === 'object' && !(item instanceof File)) {
            this.objectToFormData(item, fd, namespace + '[' + index + ']')
          } else {
            // 若是数组则在关键字后面加上[]
            fd.append(namespace + '[' + index + ']', item)
          }
        })
      } else {
        for (let property in obj) {
          if (obj.hasOwnProperty(property) && obj[property]) {

            if (namespace) {
              // 若是对象，则这样
              formKey = namespace + '[' + property + ']'
            } else {
              formKey = property
            }

            // if the property is an object, but not a File,
            // use recursivity.
            if (typeof obj[property] === 'object' && !(obj[property] instanceof File)) {
              // 此处将formKey递归下去很重要，因为数据结构会出现嵌套的情况
              this.objectToFormData(obj[property], fd, formKey)
            } else {
              // if it's a string or a File object
              fd.append(formKey, obj[property])
            }

          }
        }
      }
      return fd
    }
  }
}