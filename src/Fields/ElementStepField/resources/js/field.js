Nova.booting((Vue, router) => {
    Vue.component('index-element-step-field', require('./components/IndexField'));
    Vue.component('detail-element-step-field', require('./components/DetailField'));
    Vue.component('form-element-step-field', require('./components/FormField'));
})
