import locale from 'element-ui/lib/locale'
// import '../../theme/index.css'
import '../../element-variables.scss'

const mapLocales = {
  'en-US': require('element-ui/lib/locale/lang/en'),
  'zh-CN': require('element-ui/lib/locale/lang/zh-CN'),
  'zh-TW': require('element-ui/lib/locale/lang/zh-TW')
}

import {
  Pagination,
  Select,
  Option,
  Input,
  Autocomplete,
  InputNumber,
  Radio,
  RadioButton,
  RadioGroup,
  Checkbox,
  CheckboxButton,
  CheckboxGroup,
  ColorPicker,
  Switch,
  Tag,
  Tabs,
  TabPane,
  Dialog,
  Steps,
  Step,
  Loading,
  Notification,
  Table,
  TableColumn,
  Alert,
  Message,
  Button,
  ButtonGroup,
  Cascader,
  Form,
  FormItem,
  Popover,
  Tooltip,
  DatePicker
} from 'element-ui'

Nova.booting(function (Vue, router) {
  locale.use(mapLocales[Nova.config.locale] || mapLocales['en-US'])
  Vue.use(Pagination)
  Vue.use(Select)
  Vue.use(Option)
  Vue.use(Input)
  Vue.use(Autocomplete)
  Vue.use(InputNumber)
  Vue.use(Radio)
  Vue.use(RadioButton)
  Vue.use(RadioGroup)
  Vue.use(Checkbox)
  Vue.use(CheckboxButton)
  Vue.use(CheckboxGroup)
  Vue.use(ColorPicker)
  Vue.use(Tag)
  Vue.use(Switch)
  Vue.use(TabPane)
  Vue.use(Tabs)
  Vue.use(Dialog)
  Vue.use(Steps)
  Vue.use(Step)

  Vue.use(Table)
  Vue.use(TableColumn)

  Vue.use(Button)
  Vue.use(ButtonGroup)

  Vue.use(Cascader)

  Vue.use(Alert)

  Vue.use(Form)
  Vue.use(FormItem)

  Vue.use(Popover)
  Vue.use(Tooltip)
  Vue.use(DatePicker)

  // Vue.use(Message)
  Vue.use(Loading.directive)

  Vue.prototype.$notify = Notification
  Vue.prototype.$message = Message
  Vue.prototype.$toasted = {
    show (message, obj) {
      return Notification({
        message, ...obj
      })
    }
  }
})