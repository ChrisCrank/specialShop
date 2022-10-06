import {ValidationObserver} from "vee-validate";
import ValidatedTextInput from "~/components/Form/ValidatedTextInput";
import ValidatedSelectInput from "~/components/Form/ValidatedSelectInput";
import ValidatedTextAreaInput from "~/components/Form/ValidatedTextAreaInput";
import ValidatedCheckboxInput from "~/components/Form/ValidatedCheckboxInput";
import ValidatedDateInput from "~/components/Form/ValidatedDateInput";

export default {
  components: {
    ValidationObserver,
    ValidatedTextInput,
    ValidatedSelectInput,
    ValidatedCheckboxInput,
    ValidatedTextAreaInput,
    ValidatedDateInput
  },
  data() {
    return {
      errors: [],
    }
  },
  mounted () {
    this.$watch(
      () => {
        return this.$refs.observer.errors
      },
      (val) => {
        this.errors = []
        for (const error in val) {
          if (val[error] && val[error].length) {
            this.errors.push(val[error][0])
          }
        }
      }
    )
  }
}
