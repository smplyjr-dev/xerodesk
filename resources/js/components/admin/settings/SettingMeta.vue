<template>
  <div class="setting-meta">
    <h4 class="mb-2">Settings</h4>

    <ul>
      <router-link to="/settings/slas" :class="{ active: parent == 'slas' }">SLA</router-link>
      <router-link to="/settings/company" :class="{ active: parent == 'company' }">Company</router-link>
      <router-link to="/settings/groups" :class="{ active: parent == 'groups' }">Groups</router-link>
      <router-link to="/settings/roles" :class="{ active: parent == 'roles' }">Roles</router-link>
      <router-link to="/settings/users" :class="{ active: parent == 'users' }">Users</router-link>
    </ul>

    <hr />
  </div>
</template>

<script>
export default {
  name: "SettingMeta",
  data: () => ({
    parent: "companies"
  }),
  mounted() {
    this.parent = this.$route.path.split("/")[2];
  }
};
</script>
