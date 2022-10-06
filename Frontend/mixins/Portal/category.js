export default {
  data () {
    return {
      category: {},
    }
  },
  computed: {
    availableCategories() {
      const res = [
      ];
      for(let i = 0; i < this.categories.length; i++){
        const path = this.categories[i]?.pathArr ?? []
        let nameArr = [];
        for(let y = 0; y < path.length; y++){
          if(path[y].length !== 0) {
            let elName = this.categories.find(e => e.id == path[y])?.name;
            if(elName?.length !== 0)
              nameArr.push(elName)
          }
        }
        nameArr.push(this.categories[i]?.name)
        if(this.category?.id !== this.categories[i]?.id)
          res.push({value: this.categories[i]?.id, text: nameArr.join('->')})
      }
      res.sort((a, b) => a.text.localeCompare(b.text))
      res.push({value: null, text: this.$tc('category-add.parent.placeholder')})
      return res;
    },
    childCategories () {
      const names = [];
      const path = this.category?.pathArr ?? []
      for(let i = 0; i < path.length; i++){
        const el = this.categories.find(e => e.id == path[i])
        const name = el?.name ?? ''
        const id = el?.id ?? ''
        if(name.length)
          names.push({name: name, id:id})
      }
      names.push({name: this.category?.name, id:this.category?.id})

      return names
    },
    childCategoriesByCategory() {
      const names = [];
      const category = this.categories.find(e => e.id === this.parent) ?? this.category;
      const path = category?.pathArr ?? []
      for(let i = 0; i < path.length; i++){
        const el = this.categories.find(e => e.id == path[i])
        const name = el?.name ?? ''
        const id = el?.id ?? ''
        if(name.length)
          names.push({name: name, id:id})
      }
      names.push({name: category?.name, id:category?.id})

      return names
    },
    availableLocales () {
      return this.$i18n.locales
    }
  }
}
