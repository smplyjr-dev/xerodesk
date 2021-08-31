<template>
  <!-- <button @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">bold</button>
  <button @click="editor.chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">italic</button>
  <button @click="editor.chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">strike</button>
  <button @click="editor.chain().focus().toggleCode().run()" :class="{ 'is-active': editor.isActive('code') }">code</button>
  <button @click="editor.chain().focus().unsetAllMarks().run()">clear marks</button>
  <button @click="editor.chain().focus().clearNodes().run()">clear nodes</button>
  <button @click="editor.chain().focus().setParagraph().run()" :class="{ 'is-active': editor.isActive('paragraph') }">paragraph</button>
  <button @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }">h1</button>
  <button @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">h2</button>
  <button @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">h3</button>
  <button @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }">h4</button>
  <button @click="editor.chain().focus().toggleHeading({ level: 5 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 5 }) }">h5</button>
  <button @click="editor.chain().focus().toggleHeading({ level: 6 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 6 }) }">h6</button>
  <button @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }">bullet list</button>
  <button @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': editor.isActive('orderedList') }">ordered list</button>
  <button @click="editor.chain().focus().toggleCodeBlock().run()" :class="{ 'is-active': editor.isActive('codeBlock') }">code block</button>
  <button @click="editor.chain().focus().toggleBlockquote().run()" :class="{ 'is-active': editor.isActive('blockquote') }">blockquote</button>
  <button @click="editor.chain().focus().setHorizontalRule().run()">horizontal rule</button>
  <button @click="editor.chain().focus().setHardBreak().run()">hard break</button>
  <button @click="editor.chain().focus().undo().run()">undo</button>
  <button @click="editor.chain().focus().redo().run()">redo</button> -->

  <EditorContent class="editor" :editor="editor" />
</template>

<script>
import { Editor, EditorContent } from "@tiptap/vue-2";
import StarterKit from "@tiptap/starter-kit";
import Placeholder from "@tiptap/extension-placeholder";

export default {
  name: "TicketReplyEditor",
  components: { EditorContent },
  props: {
    value: {
      type: String,
      default: ""
    },
    emoji: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      editor: null
    };
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
    },
    emoji(value) {
      // prettier-ignore
      this.editor.chain().focus().insertContent(value).run();
    }
  },
  mounted() {
    let self = this;
    let editor = new Editor({
      extensions: [StarterKit, Placeholder],
      content: this.value,
      editorProps: {
        handleKeyDown(view, event) {
          if (!event.shiftKey && event.key === "Enter") {
            self.$emit("onEnter");
            return true;
          }

          return false;
        }
      }
    });

    editor.on("update", e => {
      // HTML
      self.$emit("input", self.editor.getHTML());

      // JSON
      // self.$emit("input", self.editor.getJSON());
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
