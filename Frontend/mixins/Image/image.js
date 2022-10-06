export default {
  data () {
    return {
      imgPath: process.env.docUrl,
    }
  },
  methods: {
    getOrgImage(obj){
      if(Array.isArray(obj))
        return obj?.find(e => e.size === 'org')?.img
      return obj;
    },
    getImgSrcSet(path, images){
      const srcSet = [];
      for (let i = 0; i < images?.length; i++){
        if(images[i].size === 'org')
          continue;
        else
          srcSet.push(path + images[i].img + ' '+images[i].size+'w')
      }
      // remove last ,
      return srcSet.join(",\n");
    },
    getSizes(small = 100, medium = 100, large = 100, extraLarge = 100) {
      const smallBP = "540";
      const mediumBP = "720";
      const largeBP = "960";
      const extraLargeBP = "1140";

      let sizes = "";
      sizes += '(max-width: '+smallBP+'px)' + small + 'vw,'
      sizes += '(max-width: '+mediumBP+'px)' + medium + 'vw,'
      sizes += '(max-width: '+largeBP+'px)' + large + 'vw,'
      sizes += '(min-width: '+extraLargeBP+'px)' + extraLarge + 'vw'
      return sizes;
    }
  }
}
