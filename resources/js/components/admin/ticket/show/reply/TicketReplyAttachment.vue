<template>
  <div class="footer-att">
    <div class="footer-att-item" v-for="(a, $a) in attachments" :key="$a" :class="{ invalid: !$isEmpty(checkValidity(a)) }">
      <div class="footer-att-item-remove" @click="removeAttachment($a)">
        <InlineSvg name="template/mdi-close-circle.svg" color="#000" size="10px" />
      </div>
      <div class="footer-att-item-icon">
        <InlineSvg :name="`heroicons/${getIcon(a.ext.toLowerCase())}.svg`" color="#000" size="15px" />
      </div>
      <div class="footer-att-item-name">
        <template v-if="!$isEmpty(checkValidity(a))">
          <span>{{ a.file.name }}</span>
          <span v-if="checkValidity(a) == 'size'">File size exceeds 2MB limit.</span>
          <span v-if="checkValidity(a) == 'type'">Invalid file type.</span>
        </template>

        <span v-else>{{ a.file.name }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["attachments"],
  name: "TicketReplyAttachment",
  data: () => ({
    extensions: ["csv", "doc", "docx", "gif", "htm", "html", "ico", "jpeg", "jpg", "png", "pdf", "pptx", "rar", "txt", "xls", "xlsx", "zip", "7z"]
  }),
  methods: {
    getIcon(ext) {
      // documents
      if (["doc", "docx", "htm", "html", "pdf", "txt", "pptx"].includes(ext)) return "document-text";

      // spread sheet
      if (["csv", "xls", "xlsx"].includes(ext)) return "document-report";

      // images
      if (["gif", "ico", "jpeg", "jpg", "png"].includes(ext)) return "photograph";

      // compressed
      if (["rar", "zip", "7z"].includes(ext)) return "folder";

      // video
      if (["mp4", "mov", "avi", "flv", "mkv", "wmv", "webm"].includes(ext)) return "video-camera";
    },
    removeAttachment(key) {
      // prettier-ignore
      this.$emit("removeAttachment", this.attachments.filter((a, i) => i != key));
    },
    checkValidity(file) {
      let size = file.file.size;
      let extension = file.ext.toLowerCase();
      let response = "";

      if (!this.extensions.includes(extension)) response = "type";
      if (size > 2000000) response = "size";

      return response;
    }
  }
};
</script>

<style lang="scss" scoped>
// =========================== >>
// Widget Footer: Attachment
// =========================== >>

.footer-att {
  display: flex;
  margin: 0px 20px 0px 20px;
  overflow-x: auto;
  padding-top: 15px;
  font-size: 0.9rem;
}

.footer-att-item {
  background: #eee;
  border-radius: 5px;
  padding: 10px 15px;
  position: relative;
  display: flex;
  align-items: center;
  margin: 5px 5px 10px 0px;
  flex-grow: 0;
  flex-shrink: 0;

  &:last-child {
    margin-right: 0px;
  }

  &.invalid {
    border: 1px solid red;
    color: red;

    .footer-att-item-icon {
      display: none;
    }
  }
}

.footer-att-item-icon {
  padding: 10px 7px 7px;
  background: #ccc;
  border-radius: 5px;
  margin-right: 10px;
}

.footer-att-item-name {
  overflow: hidden;

  span {
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    display: -webkit-box;
    line-height: 15px;
  }
}

.footer-att-item-remove {
  border-radius: 100%;
  background: #ccc;
  cursor: pointer;
  padding: 3px;
  height: 15px;
  width: 15px;

  display: flex;
  align-items: center;
  justify-content: center;

  position: absolute;
  top: -5px;
  right: -5px;
}
</style>
