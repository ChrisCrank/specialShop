export default {
  methods: {
    closeImagePrev () {
      document.getElementById('imagePrevWrapperImg').src = "#";
      document.getElementById('imagePrevWrapper').classList.remove('imagePreviewContainerShow');
      document.getElementById('descriptionPrevWrapperImg').innerHTML = "";
      this.imagePrevOpen = false;
    },
    openImagePrev (img,description) {
      let el = document.getElementById('imagePrevWrapper');
      let imgEl = document.getElementById('imagePrevWrapperImg');
      let descriptionEl = document.getElementById('descriptionPrevWrapperImg');
      let closeEl = document.getElementById('imagePrevWrapperClose');
      if(!el){
        el = document.createElement('div');
        el.classList.add('imagePreviewContainer')
        el.id = "imagePrevWrapper";
        imgEl = document.createElement('img');
        imgEl.id = "imagePrevWrapperImg";
        descriptionEl = document.createElement('div');
        descriptionEl.id = 'descriptionPrevWrapperImg';
        closeEl = document.createElement('div');
        closeEl.id = 'imagePrevWrapperClose';
        closeEl.classList.add('position-absolute','p-4','text-danger','closeCross')
        el.appendChild(imgEl)
        el.appendChild(descriptionEl)
        el.appendChild(closeEl)
        el.onclick = () => {
          this.closeImagePrev()
        }
        document.body.appendChild(el);
      }
      el.classList.add('imagePreviewContainerShow')
      imgEl.src = img;
      descriptionEl.innerHTML = description;
      this.imagePrevOpen = true;
    }
  }
}
