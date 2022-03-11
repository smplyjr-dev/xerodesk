<template>
  <div class="card mx-4">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-7">
          <form @submit.prevent="">
            <div class="form-group">
              <label for="title">Title <span class="text-danger">*</span></label>
              <input id="title" type="text" class="form-control" v-model="title" />
            </div>
            <div class="form-group">
              <label for="sub_title">Sub Title <span class="text-danger">*</span></label>
              <input id="sub_title" type="text" class="form-control" v-model="sub_title" />
            </div>
            <div class="form-group">
              <label>Icon <span class="text-danger">*</span></label>
              <div class="flex-center">
                <div style="background: rgba(0, 0, 0, 0.25); padding: 0.5rem">
                  <img loading="lazy" class="object-contain" :src="icon.file" @error="$onImgError($event, 3)" height="50px" width="50px" alt="Profile Picture" />
                </div>
                <div class="ml-2">
                  <input type="file" class="d-none" ref="file" @change="onFileChange" />
                  <button type="button" @click="handleLogo('change')" class="border-0 badge badge-pill" style="background: #dee2e6">Choose a logo</button>
                  <button type="button" @click="handleLogo('remove')" class="border-0 badge badge-pill" style="background: #dee2e6">Remove</button>
                </div>
              </div>
            </div>
            <!-- prettier-ignore -->
            <ColorPicker
              :title="c.title"
              :sub_title="c.sub_title"
              @input="c.value = $event"
              :value="c.value"
              v-for="(c, cIdx) in colors"
              :key="cIdx"
              :class="{ 'mb-0': cIdx == colors - 1 }"
            />
          </form>
        </div>
        <div class="col-sm-5 align-self-center">
          <div class="widget">
            <div class="widget-content">
              <div class="widget-header" :style="{ background: colors.primary.value }">
                <div class="icon">
                  <img loading="lazy" class="object-contain" :src="icon.file" @error="$onImgError($event, 3)" height="50px" width="50px" alt="Profile Picture" />
                </div>
                <div class="heading">
                  <span class="title" :style="{ color: colors.heading.value }">{{ title }}</span>
                  <span class="sub_title" :style="{ color: colors.heading.value }">{{ sub_title }}</span>
                </div>
              </div>
              <div class="widget-body">
                <div class="bubble bubble--external">
                  <div class="content" :style="{ background: colors.primary.value, color: colors.heading.value }">Hello! How are you?</div>
                </div>
                <div class="bubble bubble--internal">
                  <div class="content" :style="{ background: colors.text_bg.value, color: colors.text_color.value }">Hello! How are you?</div>
                </div>
              </div>
              <div class="widget-footer">
                <button class="attm" :style="{ background: colors.btn_bg.value, color: colors.heading.value }">
                  <InlineSvg name="svg/mdi/plus.svg" size="1.25rem" />
                </button>
                <div class="input">
                  <input type="text" class="form-control" placeholder="Type a message here" />
                </div>
                <div class="flex-center">
                  <button class="emoji mr-1" style="color: #cccccc">
                    <InlineSvg name="svg/mdi/emoticon-happy-outline.svg" size="1.5rem" />
                  </button>
                  <button class="send" :style="{ background: colors.btn_bg.value, color: colors.heading.value }">
                    <InlineSvg name="svg/mdi/navigation-variant.svg" size="1rem" />
                  </button>
                </div>
              </div>
            </div>
            <div class="widget-toggler" :style="{ background: colors.primary.value, color: colors.heading.value }">
              <InlineSvg name="svg/mdi/close.svg" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-right">
      <button class="btn btn-primary">Update</button>
    </div>
  </div>
</template>

<script>
import ColorPicker from "@Components/admin/settings/ColorPicker.vue";

export default {
  layout: "Admin",
  name: "SettingWidget",
  metaInfo: () => ({ title: "Widget Configuration" }),
  middleware: ["auth"],
  components: { ColorPicker },
  data: () => ({
    title: "Xerodesk",
    sub_title: "Lorem ipsum dolor amet",
    icon: {
      extension: null,
      size: null,
      name: null,
      file: ""
    },
    colors: {
      primary: {
        title: "Primary",
        sub_title: "Change the primary theme color of your widget",
        value: "#f68a1f"
      },
      heading: {
        title: "Heading",
        sub_title: "Change the color of the heading",
        value: "#ffffff"
      },
      text_bg: {
        title: "Text Background",
        sub_title: "Change the background color of the text",
        value: "#e9e9e9"
      },
      text_color: {
        title: "Text Color",
        sub_title: "Change the color of the text",
        value: "#4b4b4b"
      },
      btn_bg: {
        title: "Button Color",
        sub_title: "Change the background color of the button",
        value: "#6f6f6f"
      }
    }
  }),
  methods: {
    handleLogo(type) {
      if (type == "change") {
        this.$refs.file.click();
      }
    },
    onFileChange(e) {
      // this.isPictureLoading = true;

      let files = e.target.files || e.dataTransfer.files;

      if (!files.length) return;

      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();

      reader.readAsDataURL(file);

      reader.onload = (e) => {
        this.icon.file = e.target.result;
      };

      this.icon.extension = file.name.split(".").pop();
      this.icon.size = file.size;
      this.icon.name = file.name.split(".").slice(0, -1).join(".");
    },
    setTogglerEvent() {
      const comm = document.querySelectorAll(".btn-toggler");

      for (let i = 0; i < comm.length; i++) {
        comm[i].addEventListener("click", function () {
          if ($(this).hasClass("show")) {
            $(this).removeClass("show");
            $(this).find(".fas").removeClass("fa-caret-up").addClass("fa-caret-down");
          } else {
            $(this).addClass("show");
            $(this).find(".fas").removeClass("fa-caret-down").addClass("fa-caret-up");
          }
        });
      }
    }
  },
  created() {
    this.$emit("setTitle", "Widget Configuration");
  },
  mounted() {
    this.setTogglerEvent();
  }
};
</script>
