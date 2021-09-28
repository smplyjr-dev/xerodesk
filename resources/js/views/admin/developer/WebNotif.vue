<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <form @submit.prevent="onSubmit">
            <div class="form-group">
              <label for="title">Title</label>
              <input id="title" type="text" class="form-control" v-model="title" />
            </div>

            <div class="form-group">
              <label for="body">Body</label>
              <textarea id="body" cols="30" rows="3" class="form-control" v-model="body"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Notify</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "Admin",
  name: "Web Notification",
  metaInfo: () => ({ title: "Web Notification" }),
  middleware: ["auth"],
  components: {},
  data: () => ({
    title: "Greetings",
    body: "Hello, there! Will the desktop notification work?"
  }),
  methods: {
    onSubmit() {
      if (!("Notification" in window)) {
        // display a notification
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid.",
          body: `Unfortunately, web notification is not supported by your device or browser.`
        });
      } else {
        Notification.requestPermission(permission => {
          let notification = new Notification(this.title, {
            body: this.body,
            icon: "https://support.filipayroll.com/images/logo.png"
          });

          // link to page on clicking the notification
          notification.onclick = () => {
            window.open(this.$APP_URL);
          };

          this.title = "";
          this.body = "";
        });
      }
    }
  }
};
</script>
