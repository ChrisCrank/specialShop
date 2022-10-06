<template>
  <div :class="'position-relative ' + $props.classes">
    <div class="customCardSliderBtn customCardSliderBtnL" @click="slideD('l')"><fa icon="angle-left"></fa></div>
    <div class="customCardSliderBtn customCardSliderBtnR" @click="slideD('r')"><fa icon="angle-right"></fa></div>
    <div class="sceneImg">
      <div class="carouselImg" id="carousel" :style="'transform: translateZ(-'+translateZ+'px) rotate(0deg)'">
        <NuxtLink :to="localePath('/'+$props.path+'/'+encodeURI(elem.name))" class="carousel__cellImage" :style="getStyleForCell(index)" v-for="(elem, index) in $props.elements" :key="'carusel' + $props.path + elem.id">
          <img
            :src="imgPath + 'img/uploads/'+getOrgImage(elem.calculatedImages)"
            :srcset="getImgSrcSet(imgPath + 'img/uploads/', elem.calculatedImages)"
            :sizes="getSizes( 100, 100, 30 ,30)"
            class="customCardImg"
            :alt="elem.name + '-Thumbnail'"
          >
          <div class="customCardImgOverlay"></div>
          <div class="customCardContent h-100 w-100 d-flex justify-content-end flex-column align-items-start">
            <div class="bottom-text p-2">
              <h5 class="text-white">{{ elem.name }}</h5>
              <p class="text-primary">{{ elem.shortDescription }}</p>
            </div>
          </div>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
<script>
import Image from "~/mixins/Image/image";
export default  {
  props: {
    elements: {
      type: Array,
      required: false
    },
    path: {
      type: String,
      required: false
    },
    classes: {
      type: String
    },
    cellSize: {
      type: Number,
      required: false,
      default: 400
    }
  },
  mixins:[Image],
  data () {
    return {
      sliderRotation: 0,
    }
  },
  computed: {
    translateZ() {
      return Math.round( ( this.$props.cellSize / 2 ) /  Math.tan( Math.PI / this.$props.elements.length ) ) + 20;
    }
  },
  methods: {
    getStyleForCell(number){
      const steps = 360 / this.$props.elements.length
      return 'transform: rotateY(  '+(steps*number)+'deg) translateZ('+this.translateZ+'px)'
    },
    slideD(direction){
      if(direction === 'r') {
        this.sliderRotation -= 360 / this.$props.elements.length
        if(this.slide - 1 < 0)
          this.slide = this.$props.elements.length-1
        else
          this.slide--
      }else{
        this.sliderRotation += 360 / this.$props.elements.length
        if(this.slide + 1 > this.$props.elements.length-1)
          this.slide = 0
        else
          this.slide++
      }
      document.getElementById('carousel').style.transform = "translateZ(-"+this.translateZ+"px) rotateY("+this.sliderRotation+"deg)"
    }
  },
}
</script>
<style lang="scss">
@import '~/assets/main.scss';
.customCardSliderBtnL{
  left:0;
}
.customCardSliderBtnR{
  right:0;
}
.customCardSliderBtn{
  position:absolute;
  top:50%;
  transform: translateY(-50%);
  color:$white;
  z-index:100;
  cursor:pointer;
  transition:0.5s;
  padding:0.5em;
  font-size:3em;
}
.customCardSliderBtn:hover{
  color: darken($white, 50%);
}
.sceneImg {
  width: 420px;
  height: 420px;
  position: relative;
  perspective: 1000px;
}

.carouselImg {
  width: 100%;
  height: 100%;
  position: absolute;
  transform-style: preserve-3d;
  transform:translateZ(-363px) rotateY(0deg);
  transition: 0.5s;
}

.carousel__cellImage {
  position: absolute;
  width: 400px;
  height: 400px;
  left: 10px;
  top: 10px;
  overflow:hidden;
  cursor:pointer;
  box-shadow: 0px 0px 10px 0px black;
  transition:0.2s;
  margin:0.5em;
  border-radius:10px;
  .customCardImg{
    position:absolute;
    top:0;
    left:0;
    height:100%;
    z-index:1;
  }
  .customCardImgOverlay{
    z-index:2;
    height:100%;
    width:100%;
    position:absolute;
    top:0;
    left:0;
    background-image: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,1));
  }
  .customCardContent{
    z-index:3;
    position:absolute;
    top:0;
    left:0;
    height:100%;
    width:100%;
  }
}
.carousel__cellImage:hover{
  border:3px solid $primary;
}
</style>
