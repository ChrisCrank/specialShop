<template>
  <div class="page">
    <div class="mainSection d-flex justify-content-center align-items-center position-relative overflow-hidden">
      <img src="http://placekitten.com/1920/900" alt="" class="mainSectionImg">
      <div class="container d-flex justify-content-center align-items-center">
        <div class="col-12 col-md-8 d-flex justify-content-center align-items-center flex-column">
          <div class="content text-outline">{{ $tc('home.section1.title') }}</div>
          <b-button class="customButton mt-3">{{ $tc('home.section1.action') }}</b-button>
        </div>
      </div>
    </div>
    <div class="w-100 d-flex justify-content-center align-items-center flex-column position-relative">
      <div class="movingBackground overflow-hidden">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
      <div class="position-relative w-100 d-flex flex-column justify-content-center align-items-center">
        <div class="w-100 flex-row flex-nowrap d-flex" style="margin-bottom:-12%">
          <img src="http://placekitten.com/850/400" alt="" style="width:50%;">
          <img src="http://placekitten.com/850/400" alt="" style="width:50%;">
        </div>
        <div class="col-10 col-md-6 bg-white meCard shadow-lg p-0 m-0">
          <div class="row m-0">
            <div class="col-12 col-md-6 p-0 m-0"><img src="http://placekitten.com/456/409" class="w-100"  alt="" style="width:50%;" v-lazy-load></div>
            <div class="col-12 col-md-6 p-2" id="about">
              <div class="content">
                <h3>{{ $tc('home.section2.card.title') }}</h3>
                <h4>{{ $tc('home.section2.card.subTitle') }}</h4>
                <hr>
                <p>{{ $tc('home.section2.card.text') }}</p>
                <b-button class="customButton mt-3">{{ $tc('home.section2.card.action') }}</b-button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="m-0 p-0 mt-5 mb-5 d-flex justify-content-center align-items-center flex-column w-100 col-12">
        <h1 class="text-white font-weight-bold text-uppercase pl-4">{{ $tc('home.section3.title') }}</h1>
        <div class="d-none d-lg-flex justify-content-center align-items-center flex-wrap flex-row col-xl-10">
          <NuxtLink :to="localePath('/Category/'+encodeURI(cat.name))" class="customCard position-relative overflow-hidden"  v-for="cat in Categories" :key="'port' + cat.id">
            <img
              :src="imgPath + 'img/uploads/'+getOrgImage(cat.calculatedImages)"
              :srcset="getImgSrcSet(imgPath + 'img/uploads/', cat.calculatedImages)"
              :sizes="getSizes(100, 50, 30 ,30)"
              class="customCardImg"
              :alt="cat.name + '-Thumbnail'"
            >
            <div class="customCardImgOverlay"></div>
            <div class="customCardContent h-100 w-100 d-flex justify-content-end flex-column align-items-start">
              <div class="bottom-text p-2">
                <h5 class="text-white">{{ cat.name }}</h5>
                <p class="text-primary">{{ cat.description }}</p>
              </div>
            </div>
          </NuxtLink>
        </div>
        <carusel :elements="Categories" path="Category" classes="d-flex d-lg-none" />
        <div class="mt-5 d-flex justify-content-center align-items-center w-100">
          <b-button class="customButton2 mt-3">{{ $tc('home.section3.action') }}</b-button>
        </div>
      </div>
    </div>
    <div class="w-100 d-flex justify-content-center align-items-center flex-column">
      <div class="m-0 p-0 mt-5 mb-5 d-flex justify-content-center align-items-center flex-column w-100 col-12 col-xl-10">
        <h1>{{ $tc('home.section4.title') }}</h1>
        <div class="row m-0 p-0 w-100">
          <div class="d-flex d-lg-none justify-content-center align-items-center w-100">
            <product-slider :products="recentProducts" classes="d-flex d-lg-none"/>
          </div>
          <div class="d-none d-lg-flex justify-content-center align-items-center flex-wrap w-100">
            <product-box v-for="(product) in recentProducts" classes="" :key="'desk'+product.id" :product="product"/>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column position-relative recent-product-container">
      <div class="customBackground overflow-hidden" />
      <h1 class="text-white font-weight-bold">{{ $tc('home.section5.title') }}</h1>
      <div class="d-flex justify-content-center align-items-stretch flex-wrap col-12 col-lg-8">
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center" v-for="usp in usps" :key="usp.title">
          <div class="shadow h-100 rounded bg-light p-2 m-2 d-flex flex-column justify-content-between align-items-center">
            <fa :icon="usp.icon" class="text-primary" style="font-size:5em"></fa>
            <h1 class="text-primary text-uppercase font-weight-bold text-center">{{ usp.title }}</h1>
            <div class="text-primary p-4">
              {{ usp.description }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="contact-container">
      <div class="row m-0 p-0">
        <div class="col-12 col-lg-6 bg-white contact-container-form d-flex justify-content-center align-items-center flex-column pb-5">
          <h3 class="text-primary">Contact Me</h3>
          <div class="d-flex justify-content-start align-items-start flex-column w-75">
            <div class="w-100">
              <label for="email">Email:</label>
              <b-form-input v-model="contactEmail" type="email" id="email" placeholder="Enter your Email" />
            </div>
            <div class="w-100 mt-2">
              <label for="name">Name:</label>
              <b-form-input v-model="contactName" id="name" placeholder="Enter your name" />
            </div>
            <div class="w-100 mt-2">
              <label for="text">Text:</label>
              <b-form-textarea
                id="text"
                class="w-100"
                v-model="contactText"
                placeholder="Enter something..."
                rows="6"
              ></b-form-textarea>
            </div>
            <b-button variant="primary" class="align-self-center text-uppercase mt-3 badge-pill">send message</b-button>
          </div>
        </div>
        <div class="col-0 col-lg-6 contact-background"></div>
      </div>
    </div>
  </div>
</template>
<script>
import {mapGetters} from "vuex";
import Carusel from "~/components/Theme/Carusel";
import ProductSlider from "~/components/Theme/Slider/ProductSlider";
import Image from "~/mixins/Image/image";
export default {
  data() {
    return {
      recentProducts: [],
      posts: [],
      contactName: null,
      contactEmail: null,
      contactText: null,
      usps: {
        1: {
          "icon":'star',
          "title":this.$tc('home.section5.1.title'),
          "description":this.$tc('home.section5.1.description'),
        },
        2: {
          "icon":'truck',
          "title":this.$tc('home.section5.2.title'),
          "description":this.$tc('home.section5.2.description'),
        },
        3: {
          "icon":'phone',
          "title":this.$tc('home.section5.3.title'),
          "description":this.$tc('home.section5.3.description'),
        },
        4: {
          "icon":'laptop',
          "title":this.$tc('home.section5.4.title'),
          "description":this.$tc('home.section5.4.description'),
        }
      }
    }
  },
  mixins:[Image],
  head() {
    const i18nHead = this.$nuxtI18nHead({ addSeoAttributes: true })
    return  {
      title: "Special Shop - Home",
      htmlAttrs: i18nHead.htmlAttrs,
      meta: [
        // hid is used as unique identifier. Do not use `vmid` for it as it will not work
        {
          hid: 'indexDescription',
          name: 'indexDescription',
          content: this.$t('content-index.intro.description')
        },
        ...i18nHead.meta
      ],
      link: [
        ...i18nHead.link
      ]
    }
  },
  layout: 'default',
  computed: {
    ...mapGetters({
      user: 'User/getUser',
      Categories: 'Categories/getCategoriesSorted'
    })
  },
  components:{
    Carusel,
    ProductSlider
  },
  async asyncData({store}) {
    const res = await store.dispatch('Product/getRecentProducts', {offset: 0, limit: 6})
    const r = [];
    for (let product in res){
      r.push(res[product])
    }
    return {
      recentProducts: r
    }
  }
}
</script>
<style scoped lang="scss">
@import '~/assets/main.scss';
.page{
  margin-top:-80px;
}
.mainSection{
  width:100%;
  height:900px;
  .content {
    color: $primary;
    font-size: 3em;
    z-index:2;
    font-weight:bold;
  }
}
.mainSectionImg{
  position:absolute;
  top:0;
  left:0;
  height:100%;
  filter:grayscale(100%);
  min-width:100%
}

.custom-bg{
  background: radial-gradient(circle, rgba(238,174,202,1) 0%, $primary 50%)
}

.meCard{
  border-radius: 15px;
  overflow:hidden;
}
.customCard {
  width: 400px;
  min-width: 400px;
  height: 400px;
  cursor:pointer;
  box-shadow: 0px 0px 10px 0px black;
  transition:0.5s;
  margin:0.5em;
  border-radius:15px;
  .customCardImg{
    position:absolute;
    top:50%;
    left:50%;
    transform:translateX(-50%) translateY(-50%);
    height:100%;
    z-index:1;
    border-radius:15px;
  }
  .customCardImgOverlay{
    z-index:2;
    height:100%;
    width:100%;
    position:absolute;
    top:0;
    left:0;
    background-image: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,1));
    border-radius:15px;
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
.customCard:hover{
  transform:scale(1.1);
  z-index:4;
}
.CategoryImg{
  border-radius: 100%;
  height:100px;
  width:100px;
  max-height:100px;
  max-width:100px;
}
.categoryEntry{
  padding-bottom:15px;
  border-bottom:1px solid rgba(0, 0, 0, 0.08);
  transition:0.5s;
  cursor:pointer;
  img{
    transition:0.5s;
    border: 2px solid transparent;
  }
  .seeMore{
    transition:0.5s;
  }
}
.categoryEntry:hover{
  img{
    border: 2px solid $primary;
  }
  .seeMore{
    color:$primary;
  }
}
.customBackground{
  height:100%;
  width:150%;
  position:absolute;
  top:0;
  left:-25%;
  transform: rotate(4deg);
  z-index:-1;
  background: radial-gradient(circle,lighten($primary,5%) 0%, $primary 100%)
}
.recent-product-container{
  margin-top:2em;
  padding-top:2em;
  padding-bottom:5em;
  margin-bottom:-5em;
  z-index:2;
  overflow-x: clip;
}
.contact-container{
  z-index:-2;
}
.contact-background{
  background:url('http://placekitten.com/900/700');
  background-size:cover;
  background-repeat: no-repeat;
  background-position: center;
  background-attachment: fixed;
  filter: grayscale(100%);
}
.contact-container-form{
  padding-top:10em;
  box-shadow: 0px 0px 5px 0px #000000;
  input,textarea{
    background-color: $light;
  }
}










.movingBackground {
  position:absolute;
  top:0;
  left:0;
  width: 100%;
  height: 100%;
  background: $primary;
}

$particleSize: 50vmin;
$animationDuration: 6s;
$amount: 5;
.movingBackground span {
  width: $particleSize;
  height: $particleSize;
  border-radius: $particleSize;
  backface-visibility: hidden;
  position: absolute;
  animation-name: move;
  animation-duration: $animationDuration;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  $colors: (
    #583C87,
    #E45A84,
    #FFACAC
  );
  @for $i from 1 through $amount {
    &:nth-child(#{$i}) {
      color: nth($colors, random(length($colors)));
      top: random(100) * 1%;
      left: random(100) * 1%;
      animation-duration: (random($animationDuration * 10) / 10) * 1s + 20s;
      animation-delay: random(($animationDuration + 10s) * 10) / 10 * -1s;
      transform-origin: (random(50) - 25) * 1vw (random(50) - 25) * 1vh;
      $blurRadius: (random() + 0.5) * $particleSize * 0.5;
      $x: if(random() > 0.5, -1, 1);
      box-shadow: ($particleSize * 2 * $x) 0 $blurRadius currentColor;
    }
  }
}

@keyframes move {
  100% {
    transform: translate3d(0, 0, 1px) rotate(360deg);
  }
}
</style>
