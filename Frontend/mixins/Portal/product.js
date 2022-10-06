import {mapGetters} from "vuex";

export default {
  computed: {
    ...mapGetters({
      categories: 'Categories/getCategories'
    }),
    currentLocale() {
      const lang = this.$i18n.locales.filter(i => i.code === this.$i18n.locale)?.[0]
      return lang ?? 'en-EN'
    },
    availableCategories() {
      const res = [];
      for (let i = 0; i < this.categories.length; i++) {
        const path = this.categories[i]?.pathArr
        let nameArr = [];
        for (let y = 0; y < path.length; y++) {
          if (path[y].length !== 0) {
            let elName = this.categories.find(e => e.id == path[y])?.name;
            if (elName?.length !== 0)
              nameArr.push(elName)
          }
        }
        nameArr.push(this.categories[i]?.name)

        res.push({value: this.categories[i]?.id, text: nameArr.join('->')})
      }
      res.sort((a, b) => a.text.localeCompare(b.text))
      return res;
    },
    childCategories() {
      const names = [];
      const path = this.category?.pathArr ?? []
      for (let i = 0; i < path.length; i++) {
        const el = this.categories.find(e => e.id == path[i])
        const name = el?.name ?? ''
        const id = el?.id ?? ''
        if (name.length)
          names.push({name: name, id: id})
      }
      names.push({name: this.category?.name, id: this.category?.id})

      return names
    }
  }
}
