<template>
  <div class="messages-footer-editor">
    <div class="textarea">
      <EditorContent class="editor" :editor="editor" />
    </div>
    <div class="formatter">
      <emoji-picker @onSelect="logEmoji">
        <i class="far fa-smile mx-1"></i>
      </emoji-picker>

      <button @click="formatter('toggleBold')" :class="{ 'is-active': editor.isActive('bold') }"><strong>B</strong></button>
      <button @click="formatter('toggleItalic')" :class="{ 'is-active': editor.isActive('italic') }"><i>I</i></button>
      <!-- <button @click="formatter('toggleStrike')" :class="{ 'is-active': editor.isActive('strike') }"><i class="fa fa-strikethrough"></i></button> -->
      <button @click="formatter('toggleCode')" :class="{ 'is-active': editor.isActive('code') }">&lsaquo;&#47;&rsaquo;</button>
      <!-- <button @click="formatter('setParagraph')" :class="{ 'is-active': editor.isActive('paragraph') }">P</button> -->
      <button @click="formatter('toggleHeading1')" :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }">H1</button>
      <button @click="formatter('toggleHeading2')" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">H2</button>
      <button @click="formatter('toggleHeading3')" :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">H3</button>
      <button @click="formatter('toggleHeading4')" :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }">H4</button>
      <button @click="formatter('toggleHeading5')" :class="{ 'is-active': editor.isActive('heading', { level: 5 }) }">H5</button>
      <button @click="formatter('toggleHeading6')" :class="{ 'is-active': editor.isActive('heading', { level: 6 }) }">H6</button>
      <button @click="formatter('toggleBulletList')" :class="{ 'is-active': editor.isActive('bulletList') }"><i class="fa fa-list"></i></button>
      <button @click="formatter('toggleOrderedList')" :class="{ 'is-active': editor.isActive('orderedList') }"><i class="fa fa-list-ol"></i></button>
      <!-- <button @click="formatter('setHorizontalRule')">Line</button> -->
      <!-- <button @click="formatter('setHardBreak')">Next Line</button> -->
      <!-- <button @click="formatter('undo')"><i class="fa fa-undo"></i></button> -->
      <!-- <button @click="formatter('redo')"><i class="fa fa-redo"></i></button> -->
      <!-- <button @click="formatter('unsetAllMarks')"><i class="fa fa-eraser"></i></button> -->
    </div>
  </div>
</template>

<script>
import { Editor, EditorContent } from "@tiptap/vue-2";
import StarterKit from "@tiptap/starter-kit";
import Placeholder from "@tiptap/extension-placeholder";
import EmojiPicker from "@Components/neutral/EmojiPicker.vue";

export default {
  name: "Editor",
  components: { EditorContent, EmojiPicker },
  props: {
    value: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      editor: null
    };
  },
  methods: {
    formatter(type) {
      // prettier-ignore
      switch (type) {
        case 'toggleBold': this.editor.chain().toggleBold().run(); break;
        case 'toggleItalic': this.editor.chain().toggleItalic().run(); break;
        // case 'toggleStrike': this.editor.chain().toggleStrike().run(); break;
        case 'toggleCode': this.editor.chain().toggleCode().run(); break;
        // case 'setParagraph': this.editor.chain().setParagraph().run(); break;
        case 'toggleHeading1': this.editor.chain().toggleHeading({ level: 1 }).run(); break;
        case 'toggleHeading2': this.editor.chain().toggleHeading({ level: 2 }).run(); break;
        case 'toggleHeading3': this.editor.chain().toggleHeading({ level: 3 }).run(); break;
        case 'toggleHeading4': this.editor.chain().toggleHeading({ level: 4 }).run(); break;
        case 'toggleHeading5': this.editor.chain().toggleHeading({ level: 5 }).run(); break;
        case 'toggleHeading6': this.editor.chain().toggleHeading({ level: 6 }).run(); break;
        case 'toggleBulletList': this.editor.chain().toggleBulletList().run(); break;
        case 'toggleOrderedList': this.editor.chain().toggleOrderedList().run(); break;
        // case 'setHorizontalRule': this.editor.chain().setHorizontalRule().run(); break;
        // case 'setHardBreak': this.editor.chain().setHardBreak().run(); break;
        // case 'undo': this.editor.chain().undo().run(); break;
        // case 'redo': this.editor.chain().redo().run(); break;
        // case 'unsetAllMarks': this.editor.chain().unsetAllMarks().run(); break;
        default:
          break;
      }

      this.editor.chain().focus();
    },
    logEmoji(e) {
      // prettier-ignore
      this.editor.chain().focus().insertContent(e).run();
      this.editor.chain().focus();
    }
  },
  watch: {
    value(value) {
      // HTML
      const isSame = this.editor.getHTML() === value;

      // JSON
      // const isSame = this.editor.getJSON().toString() === value.toString()

      if (isSame) {
        return;
      }

      this.editor.commands.setContent(this.value, false);
    }
  },
  created() {
    let self = this;
    let editor = new Editor({
      extensions: [
        StarterKit,
        Placeholder.configure({
          placeholder: "Type your reply here..."
        })
      ],
      content: this.value,
      editorProps: {
        handleKeyDown(view, event) {
          // if (!event.shiftKey && event.key === "Enter") {
          //   self.$emit("onEnter");
          //   return true;
          // }

          return false;
        }
      }
    });

    editor.on("update", e => {
      /* HTML */ self.$emit("input", self.editor.getHTML());
      /* JSON */ self.$emit("input", self.editor.getJSON());
    });

    this.editor = editor;
  },
  beforeDestroy() {
    this.editor.destroy();
  }
};
</script>

<style lang="scss" scoped>
::v-deep {
  /* Basic editor styles */
  .ProseMirror {
    > * + * {
      margin-top: 0.75em;
    }

    &.ProseMirror-focused {
      outline: none;
    }

    code {
      background-color: rgba(#616161, 0.1);
      color: #616161;
    }

    p {
      margin: 0px;

      &.is-empty {
        overflow: hidden;
      }
    }
  }

  /* Placeholder (at the top) */
  .ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #ced4da;
    pointer-events: none;
    height: 0;
  }
}
</style>
