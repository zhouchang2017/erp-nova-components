export default {
  props: ['resourceName', 'resourceId', 'field'],

  provide () {
    return {
      resourceName: this.resourceName,
      resourceId: this.resourceId,
      field: this.field
    }
  },

  data () {
    return {
      warehouse: {},
      resource: {},
      address: {},
      initialLoading: true
    }
  },
  methods: {
    fetchResource () {
      return Nova.request().get(this.fetchResourceApi)
        .then(({data}) => {
          return data
        }).catch(({response}) => {
          console.error(response)
        })
    },
    setAddress () {
      const {shipment} = this.resource
      this.warehouse = {
        name: '收件地址名称',
        attribute: 'warehouse',
        value: shipment.name
      }
      this.address = {
        name: '地址',
        attribute: 'address',
        value: shipment.simple_address
      }
    },
    initItemUnit () {
      this.resource.items.forEach(item => {
        _.tap(_.get(item, 'units', []), units => {
          units.forEach(unit => {
            if (_.isNull(unit.shipment)) {
              _.set(unit, 'shipment', {id: null, logistic_id: null, tracking_number: ''})
            }
          })
        })
      })
    }
  },

  computed: {
    fetchResourceApi () {
      return `${Nova.config['erp-prefix']}/${this.resourceName}/${this.resourceId}`
    },
    toName () {
      return _.get(this.resource, 'warehouse.name', '')
    },
    // 是否已完成发货
    alreadyShipped () {
      return _.get(this.field, 'shipped')
    },
    // 发货请求api
    shipmentApi () {
      return `${Nova.config['erp-prefix']}/${this.resourceName}/${this.resourceId}/shipment`
    },

    authorizedToUpdate () {
      return _.get(this, 'field.authorizedToUpdate', false)
    }
  }
}