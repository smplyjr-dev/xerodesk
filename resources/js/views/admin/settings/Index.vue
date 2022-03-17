<template>
  <div class="container-fluid px-4">
    <div class="card-settings" v-for="(setting, settingIndex) in settings" :key="settingIndex">
      <h5 class="d-none mb-0">{{ settingIndex.replace(/_/g, " ") | capitalize }} Settings</h5>

      <div class="row">
        <router-link :to="`/settings/${s.link}`" class="col-md-3 my-2 link-unstyled" v-for="s in setting" :key="s.title">
          <div class="card-setting">
            <div class="content">
              <h5 class="title">{{ s.title }}</h5>
              <p class="sub_title">{{ s.sub_title }}</p>
            </div>
            <div class="icon">
              <InlineSvg :name="`svg/mdi/${s.icon}.svg`" size="4rem" />
            </div>
          </div>
        </router-link>
      </div>
    </div>

    <!-- <div class="card-settings">
      <h5 class="d-none mb-0">Developer</h5>

      <div class="row">
        <router-link to="/developers/webnotif" class="col-sm-3 my-2 link-unstyled">
          <div class="card-setting">
            <InlineSvg name="svg/mdi/account-group.svg" size="4rem" />
            <div class="content">
              <h5 class="title">Web Notification</h5>
              <p class="sub_title">Configure your default Operating System notification here.</p>
            </div>
          </div>
        </router-link>
      </div>
    </div> -->
  </div>
</template>

<script>
export default {
  layout: "Admin",
  name: "Dashboard",
  metaInfo: () => ({ title: "Dashboard" }),
  middleware: ["auth"],
  data() {
    return {
      // prettier-ignore
      settings: {
        account: [
          { link: "company", icon: "office-building-cog", title: "Company", sub_title: "Configure your app to add finish it's identity" },
        ],
        team: [
          { link: "groups", icon: "account-group", title: "Groups", sub_title: "Organize your users by setting them to a group" },
          { link: "roles", icon: "shield-account", title: "Roles", sub_title: "Provide and restrict levels of access to your users" },
          { link: "users", icon: "account-multiple", title: "Users", sub_title: "Create, update or delete a user from your app" },
        ],
        live_chat: [
          { link: "slas", icon: "calendar-clock", title: "SLA Policies", sub_title: "Setup targets to guarantee timely responses and solutions to customers" },
          { link: "widget/customize", icon: "widget", title: "Widget Customization", sub_title: "Configure the looks and feel of your chat widget here" },
          { link: "widget/integrate", icon: "code-tags", title: "Widget Integration", sub_title: "Copy and paste the script from here to your web app" },
        ]
      }
    };
  },
  created() {
    this.$emit("setTitle", "Settings");
  }
};
</script>
