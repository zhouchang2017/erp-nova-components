import Translatable from './translatable'
Nova.booting((Vue, router) => {
    Vue.mixin(Translatable)
    Vue.component('index-product-options-field', require('./components/IndexField'));
    Vue.component('detail-product-options-field', require('./components/DetailField'));
    Vue.component('form-product-options-field', require('./components/FormField'));
})
