<template>
  <div class="modal fade" id="attm-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Attachment</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$emit('emitAttachments', local_attachments)">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="upload-wrapper" @drop.prevent="addFile" @dragover.prevent>
            <div v-if="$isEmpty(local_attachments)">
              <h3>Drag &amp; drop your files here</h3>
            </div>

            <div class="footer-att" v-else>
              <div class="footer-att-item" v-for="(a, $a) in local_attachments" :key="$a" :class="{ invalid: !$isEmpty(checkValidity(a)) }">
                <div class="footer-att-item-remove" @click="removeAttachment(a)">
                  <InlineSvg name="template/mdi-close-circle.svg" color="#000" size="10px" />
                </div>
                <div class="footer-att-item-icon">
                  <InlineSvg :name="`heroicons/${getIcon(a.ext)}.svg`" color="#000" size="15px" />
                </div>
                <div class="footer-att-item-name">
                  <template v-if="!$isEmpty(checkValidity(a))">
                    <span>Error.</span>
                    <span v-if="checkValidity(a) == 'size'">File too large.</span>
                    <span v-if="checkValidity(a) == 'type'">Invalid file type.</span>
                  </template>

                  <span v-else>{{ a.file.name }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["attachments"],
  name: "TicketReplyAttachment",
  data: () => ({
    extensions: ["csv", "doc", "docx", "gif", "htm", "html", "ico", "jpeg", "jpg", "png", "pdf", "pptx", "rar", "txt", "xls", "xlsx", "zip", "7z"],
    local_attachments: []
  }),
  methods: {
    addFile(e) {
      let droppedFiles = e.dataTransfer.files;
      if (!droppedFiles) return;

      // this tip, convert FileList to array, credit: https://www.smashingmagazine.com/2018/01/drag-drop-file-uploader-vanilla-js/
      [...droppedFiles].forEach(f => {
        this.local_attachments.push({
          file: f,
          ext: f.name.split(".").pop()
        });
      });
    },
    checkValidity(file) {
      let size = file.file.size;
      let extension = file.ext;
      let response = "";

      if (!this.extensions.includes(extension)) response = "type";
      if (size > 20000000) response = "size";

      return response;
    },
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
    removeAttachment(file) {
      this.local_attachments = this.local_attachments.filter(f => f != file);
    }
  },
  watch: {
    attachments(newValue) {
      this.local_attachments = newValue;
    }
  }
};
</script>

<style lang="scss" scoped>
// =========================== >>
// Widget Footer: Attachment
// =========================== >>

.footer-att {
  width: 100%;
  margin: 0;
  padding: 0;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
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
  flex-basis: 32%;
  width: 32%;

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
