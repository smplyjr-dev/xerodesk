<template>
  <div class="card mx-4">
    <form @submit.prevent="updateWidget">
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
                    <img loading="lazy" class="object-contain" :src="`${this.$APP_URL}/storage/uploads/widgets/${id}/${picture}`" @error="$onImgError($event, 3)" height="50px" width="50px" alt="Profile Picture" />
                  </div>
                  <div class="ml-2">
                    <input type="file" class="d-none" ref="file" @change="onFileChange" />
                    <button type="button" @click="handleLogo('change')" class="border-0 badge badge-pill text-secondary px-3 mb-2" style="background: #dee2e6">Choose a logo</button>
                    <button type="button" @click="handleLogo('remove')" class="border-0 badge badge-pill text-secondary px-3 mb-2" style="background: #dee2e6">Remove</button>
                    <div class="text-xxs text-muted font-weight-light" style="line-height: 12px">
                      <p class="mb-0">Recommended size: 80x80</p>
                      <p class="mb-0">Only: jpeg, png</p>
                    </div>
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
                <div class="widget-header" :style="{ background: colors.theme_bg.value }">
                  <div class="icon">
                    <img loading="lazy" class="object-contain" :src="`${this.$APP_URL}/storage/uploads/widgets/${id}/${picture}`" @error="$onImgError($event, 3)" height="45px" width="45px" alt="Profile Picture" />
                  </div>
                  <div class="heading">
                    <span class="title" :style="{ color: colors.theme_color.value }">{{ title }}</span>
                    <span class="sub_title" :style="{ color: colors.theme_color.value }">{{ sub_title }}</span>
                  </div>
                </div>
                <div class="widget-body">
                  <div class="bubble bubble--external">
                    <div class="content" :style="{ background: colors.theme_bg.value, color: colors.theme_color.value }">Hello! How are you?</div>
                  </div>
                  <div class="bubble bubble--internal">
                    <div class="content" :style="{ background: colors.text_bg.value, color: colors.text_color.value }">Hello! How are you?</div>
                  </div>
                </div>
                <div class="widget-footer">
                  <button class="attm" :style="{ background: colors.button_bg.value, color: colors.theme_color.value }">
                    <InlineSvg name="svg/mdi/plus.svg" size="1.25rem" />
                  </button>
                  <div class="input">
                    <input type="text" class="form-control" placeholder="Type a message here" />
                  </div>
                  <div class="flex-center">
                    <button class="emoji mr-1" style="color: #cccccc">
                      <InlineSvg name="svg/mdi/emoticon-happy-outline.svg" size="1.5rem" />
                    </button>
                    <button class="send" :style="{ background: colors.button_bg.value, color: colors.theme_color.value }">
                      <InlineSvg name="svg/mdi/navigation-variant.svg" size="1rem" />
                    </button>
                  </div>
                </div>
              </div>
              <div class="widget-toggler" :style="{ background: colors.theme_bg.value, color: colors.theme_color.value }">
                <InlineSvg name="svg/mdi/close.svg" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</template>

<script>
import ColorPicker from "@Components/admin/settings/ColorPicker.vue";

export default {
  layout: "Admin",
  name: "SettingWidgetCustomize",
  metaInfo: () => ({ title: "Widget Configuration" }),
  middleware: ["auth"],
  components: { ColorPicker },
  data: () => ({
    id: null,
    nanoid: null,
    title: null,
    sub_title: null,
    icon: {
      method: null,
      extension: null,
      size: null,
      name: null,
      file: "",
      height: null,
      width: null
    },
    colors: {
      theme_bg: {
        title: "Theme Background",
        sub_title: "Change the primary theme color of your widget",
        value: "#f68a1f"
      },
      theme_color: {
        title: "Theme Color",
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
      button_bg: {
        title: "Button Color",
        sub_title: "Change the background color of the button",
        value: "#6f6f6f"
      }
    },
    picture: null
  }),
  methods: {
    handleLogo(type) {
      if (type == "change") {
        this.icon.method = "update";
        this.$refs.file.click();
      } else {
        this.icon.method = "remove";
        if (this.picture != "photograph.png") this.updatePicture();
      }
    },
    onFileChange(e) {
      let files = e.target.files || e.dataTransfer.files;

      if (!files.length) return;

      this.createImage(files[0]);
    },
    createImage(file) {
      let image = new Image();
      let reader = new FileReader();

      reader.readAsDataURL(file);

      reader.onload = async (e) => {
        image.src = e.target.result;

        // adding this because the width and height cannot be fetch properly
        await this.$delay(100);

        this.icon.extension = file.name.split(".").pop();
        this.icon.file = e.target.result;
        this.icon.name = file.name.split(".").slice(0, -1).join(".");
        this.icon.size = file.size;
        this.icon.height = image.height;
        this.icon.width = image.width;
        this.updatePicture();
      };
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
    },
    async updatePicture() {
      let params = {
        method: this.icon.method,
        id: this.id,
        image: this.icon.file,
        extension: this.icon.extension,
        name: this.icon.name,
        old: this.picture,
        size: this.icon.size,
        height: this.icon.height,
        width: this.icon.width
      };

      try {
        await axios.post(`/widget/picture`, params);
        await this.fetchWidget();

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check-circle",
          title: "Success!",
          body: `Widget icon has been successfully updated.`
        });
      } catch (error) {
        let errorObj = error.response.data.errors;
        let pictureError = [];

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              pictureError.push(message);
            });
          }
        }

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-exclamation-triangle",
          title: "Heads up!",
          body: pictureError.join("<br />")
        });
      }
    },
    async fetchWidget() {
      let { data } = await axios.get(`/widget`);

      this.id = data[0].id;
      this.nanoid = data[0].nanoid;
      this.title = data[0].title;
      this.sub_title = data[0].sub_title;
      this.colors.theme_bg.value = data[0].theme_bg;
      this.colors.theme_color.value = data[0].theme_color;
      this.colors.text_bg.value = data[0].text_bg;
      this.colors.text_color.value = data[0].text_color;
      this.colors.button_bg.value = data[0].button_bg;
      this.picture = data[0].picture;
    },
    async updateWidget() {
      let { data } = await axios.put(`/widget/${this.nanoid}`, {
        title: this.title,
        sub_title: this.sub_title,
        theme_bg: this.colors.theme_bg.value,
        theme_color: this.colors.theme_color.value,
        text_bg: this.colors.text_bg.value,
        text_color: this.colors.text_color.value,
        button_bg: this.colors.button_bg.value
      });

      this.$store.dispatch("notifications/addNotification", {
        variant: "bg-success",
        icon: "fa-check-circle",
        title: "Success!",
        body: "The widget has been successfully updated"
      });
    }
  },
  created() {
    this.$emit("setTitle", "Widget Customization");
    this.fetchWidget();
  },
  mounted() {
    this.setTogglerEvent();
  }
};
</script>
