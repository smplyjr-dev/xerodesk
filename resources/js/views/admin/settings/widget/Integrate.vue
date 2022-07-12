<template>
  <div class="card mx-4">
    <div class="card-body">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> HTML Code </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"> Wordpress </a>
        </li>
      </ul>
      <hr />
      <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <ul class="timeline mb-0">
            <li>
              <div class="with-buttons">
                <div class="titles">
                  <h5>1. Copy the script</h5>
                  <p class="text-muted">We made it easy for you.</p>
                </div>
                <div class="buttons">
                  <button class="btn btn-primary btn-sm" @click="copy">Copy <i class="fas fa-clipboard ml-1"></i></button>
                </div>
              </div>
              <pre class="mb-0"><code class="hljs language-xml" v-html="hljs_script"></code></pre>
            </li>
            <li>
              <h5>2. Paste the script</h5>
              <p class="text-muted">We recommend to paste the script before the body tag ends. By doing this, we don't block your website requests from loading first.</p>
              <pre class="mb-0"><code class="hljs language-xml" v-html="hljs_paste"></code></pre>
            </li>
          </ul>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <p>This is not yet available. Please subscribe to our announcement.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import hljs from "highlight.js/lib/core";
import xml from "highlight.js/lib/languages/xml";
hljs.registerLanguage("xml", xml);

export default {
  layout: "Admin",
  name: "SettingWidgetIntegrate",
  metaInfo: () => ({ title: "Widget Integrations" }),
  middleware: ["auth"],
  data() {
    return {
      widget: {}
    };
  },
  computed: {
    script() {
      return `<script src="${this.$APP_URL}/widget/xerodesk.js?key=${this.widget.nanoid}"><\/script>`;
    },
    hljs_script() {
      return hljs.highlight(this.script, { language: "xml" }).value;
    },
    paste() {
      let html = "";
      html += `<body>\n`;
      html += `\n`;
      html += `  <!-- Your entire code here -->\n`;
      html += `\n`;
      html += `  <!-- Xerodesk script -->\n`;
      html += `  ${this.script}\n`;
      html += `\n`;
      html += `</body>`;

      return html;
    },
    hljs_paste() {
      return hljs.highlight(this.paste, { language: "xml" }).value;
    }
  },
  created() {
    this.$emit("setTitle", "Widget Integration");
    this.fetchWidget();
  },
  methods: {
    copy() {
      navigator.clipboard.writeText(this.script).then((r) => {
        this.$store.dispatch("notifications/successNotification", {
          title: "Success!",
          body: "The script has been copied to your clipboard."
        });
      });
    },
    async fetchWidget() {
      let { data } = await axios.get(`/widget`);

      this.widget = data[0];
    }
  }
};
</script>

<style lang="scss">
@import "@Styles/components/_highlightjs.scss";
</style>
