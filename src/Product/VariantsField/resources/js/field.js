Nova.booting((Vue, router) => {
    Vue.component('index-product-variants-field', require('./components/IndexField'));
    Vue.component('detail-product-variants-field', require('./components/DetailField'));
    Vue.component('form-product-variants-field', require('./components/FormField'));
})
