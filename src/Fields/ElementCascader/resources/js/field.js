Nova.booting((Vue, router) => {
  Vue.component('index-element-cascader', require('./components/IndexField'));
  Vue.component('detail-element-cascader', require('./components/DetailField'));
  Vue.component('form-element-cascader', require('./components/FormField'));
})
