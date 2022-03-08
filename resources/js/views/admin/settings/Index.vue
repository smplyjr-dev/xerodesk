<template>
  <div class="container-fluid px-4">
    <div class="card-settings" v-for="(setting, settingIndex) in settings" :key="settingIndex">
      <h4 class="card-settings-title">{{ settingIndex.replace(/_/g, " ") | capitalize }}</h4>

      <div class="row">
        <router-link :to="`/settings/${s.link}`" class="col-sm-3 my-2 link-unstyled" v-for="s in setting" :key="s.title">
          <div class="card-setting">
            <InlineSvg :name="`template/${s.icon}.svg`" size="6rem" />
            <h4 class="title">{{ s.title }}</h4>
            <p class="sub_title">{{ s.sub_title }}</p>
          </div>
        </router-link>
      </div>
    </div>

    <div class="card-settings">
      <h4 class="card-settings-title">Developer</h4>

      <div class="row">
        <router-link to="/developers/webnotif" class="col-sm-3 my-2 link-unstyled">
          <div class="card-setting">
            <InlineSvg name="template/mdi-account-group.svg" size="6rem" />
            <h4 class="title">Web Notification</h4>
            <p class="sub_title">Configure your default Operating System notification here.</p>
          </div>
        </router-link>
      </div>
    </div>
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
        team: [
          { link: "users", icon: "mdi-account-multiple", title: "Users", sub_title: "Create, update or delete a user from your app." },
          { link: "groups", icon: "mdi-account-group", title: "Groups", sub_title: "Organize your users by setting them to a group." },
          { link: "roles", icon: "mdi-shield-account", title: "Roles", sub_title: "Provide and restrict levels of access to your users." },
        ],
        account: [
          { link: "company", icon: "mdi-office-building-cog", title: "Company", sub_title: "Configure your app to add finish it's identity." },
        ],
        live_chat: [
          { link: "script", icon: "mdi-code-tags", title: "Script", sub_title: "Copy and paste the script from here to your web app." },
          { link: "slas", icon: "mdi-calendar-clock", title: "SLA Policies", sub_title: "Setup targets to guarantee timely responses and solutions to customers." },
        ]
      }
    };
  },
  created() {
    this.$emit("setTitle", "Settings");
  }
};
</script>
