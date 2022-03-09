<template>
  <div class="flex-center-center inline-svg h-auto" v-html="svg" :style="{ color: color }"></div>
</template>

<script>
class Svg {
  constructor(name) {
    let svg = require(`@Public/images/${name}`);

    let div = document.createElement("div");
    div.innerHTML = svg;

    let fragment = document.createDocumentFragment();
    fragment.appendChild(div);

    this.svg = fragment.querySelector("svg");
  }

  classes(classes) {
    if (classes) {
      this.svg.classList.add(classes);
    }

    return this;
  }

  height(height) {
    if (height) {
      this.svg.setAttribute("height", height);
    }

    return this;
  }

  width(width) {
    if (width) {
      this.svg.setAttribute("width", width);
    }

    return this;
  }

  getHtml(getHtml) {
    return this.svg.outerHTML;
  }
}

export default {
  name: "InlineSvg",
  props: {
    name: {
      required: true,
      type: String
    },
    classes: {
      required: false,
      type: String
    },
    width: {
      default: "24px",
      required: false,
      type: String
    },
    height: {
      default: "24px",
      required: false,
      type: String
    },
    size: {
      default: null,
      required: false,
      type: String
    },
    color: {
      required: false,
      type: String
    }
  },
  data: () => ({
    svg: ""
  }),
  created() {
    if (this.$isNull(this.size)) {
      this.svg = new Svg(this.name)
        .classes(this.classes)
        .height(this.height)
        .width(this.width)
        .getHtml();
    } else {
      this.svg = new Svg(this.name)
        .classes(this.classes)
        .height(this.size)
        .width(this.size)
        .getHtml();
    }
  }
};
</script>
