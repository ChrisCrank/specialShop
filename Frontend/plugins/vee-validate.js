import { configure, extend, localize } from 'vee-validate'
import { alpha, confirmed, email, integer, max, min, required, alpha_num, numeric, decimal } from 'vee-validate/dist/rules'

import de from '~/lang/vee-validate/de.json'
import en from '~/lang/vee-validate/en.json'

extend('required', {
  ...required
  // message: 'This field is required'
})

extend('integer', {
  ...integer
  // message: 'This field is required'
})
extend('numeric', {
  ...numeric
  // message: 'This field is required'
})
extend('decimal', {
  validate: (value, { decimals = '*', separator = '.' } = {}) => {
    if (value === null || value === undefined || value === '') {
      return {
        valid: false
      };
    }
    if (Number(decimals) === 0) {
      return {
        valid: /^-?\d*$/.test(value),
      };
    }
    const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;
    const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);

    return {
      valid: regex.test(value),
    };
  },
  message: 'The {_field_} field must contain only decimal values'
  // message: 'This field is required'
})

extend('alpha', {
  ...alpha
  // message: 'This field must only contain alphabetic characters'
})

extend('email', {
  ...email
  // message: 'Please enter a valid e-mail address.'
}, {
  initial: false
})

extend('min', {
  ...min
  // message: 'Too short'
})

extend('max', {
  ...max
  // message: 'Too long'
})

extend('confirmed', {
  ...confirmed
  // message: 'Passwords must match'
})
extend('alpha_num', {
  ...alpha_num
})
extend('date', {
  validate: value =>
    /^\d{4}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/.test(
      value
    ),
  message: 'Date must be in YYYY-MM-DD format'
}, {
  initial: false
})

extend('verify_url',{
  getMessage: field => 'You have to specify a url',
  validate: (value) => {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
      '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
      '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
      '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
      '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
      '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(value);
  }
})

extend('verify_creditcard',{
  getMessage: field => 'The Credit Card Number have to be valid',
  validate: (value) => {
    const regex = new RegExp('/^(?:4[0-9]{12}(?:[0-9]{3})?)$/')
    return regex.test(value)
  }
})

const forValidator = {
  'de': { veeDefaultName: 'de-DE', dic: de },
  'en': { veeDefaultName: 'en-EN', dic: en }
}

export default function ({ app }) {
  // setInteractionMode('lazy')
  const locale = app.i18n.locale
  localize(forValidator[locale].veeDefaultName, forValidator[locale].dic)

  // beforeLanguageSwitch called right before setting a new locale
  app.i18n.beforeLanguageSwitch = (oldLocale, newLocale) => {
    localize(forValidator[newLocale].veeDefaultName, forValidator[newLocale].dic)
  }
  configure({
    generateMessage: (context) => {
      const field = context.field
      const values = context.values

      return `The field ${context.field} is invalid`;
    },
    defaultMessage: (field, values, test) => {
      // override the field name.
      // values._field_ = app.i18n.t(`fields.${field}`)

      const snippet = forValidator?.[locale]?.dic?.messages?.[values._rule_]?.replace('{_field_}',field)?.replace('{length}',values.length).replace('{max}', values.max)
      const additional = app.i18n.t(`validation.${values._rule_}`, { _field_: field, length: values.length })
      return additional !== 'validation.' + values._rule_ ? additional : snippet
    }
  })

}
