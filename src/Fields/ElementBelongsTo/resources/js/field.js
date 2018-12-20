Nova.booting((Vue, router) => {
  Vue.component('index-element-belongs-to-field', require('./components/IndexField'));
  Vue.component('detail-element-belongs-to-field', require('./components/DetailField'));
  Vue.component('form-element-belongs-to-field', require('./components/FormField'));
})
