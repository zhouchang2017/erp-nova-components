Nova.booting((Vue, router) => {
    Vue.component('index-product-attributes-field', require('./components/IndexField'));
    Vue.component('detail-product-attributes-field', require('./components/DetailField'));
    Vue.component('form-product-attributes-field', require('./components/FormField'));
})
