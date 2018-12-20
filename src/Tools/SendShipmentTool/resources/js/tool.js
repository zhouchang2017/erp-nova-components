Nova.booting((Vue, router) => {
  Vue.component('send-shipment-tool', require('./components/Tool'))

  router.addRoutes([
    {
      name: 'shipment.detail',
      path: '/resources/:resourceName/:resourceId/shipment',
      component: require('./views/shipment/Detail'),
      props: route => {
        return {
          resourceName: route.params.resourceName,
          resourceId: route.params.resourceId,
        }
      }
    },
    {
      name: 'shipment.update',
      path: '/resources/:resourceName/:resourceId/shipment/edit',
      component: require('./views/shipment/Update'),
      props: route => {
        return {
          resourceName: route.params.resourceName,
          resourceId: route.params.resourceId,
        }
      }
    }
  ])
})
