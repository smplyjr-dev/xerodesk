<template>
  <div class="col-md-3">
    <div class="setting-meta">
      <!-- <h4 class="mb-2">Settings</h4> -->

      <div class="card card-body p-0">
        <ul>
          <router-link to="/settings/slas" :class="{ active: parent == 'slas' }">Service-level Agreements</router-link>
          <router-link to="/settings/company" :class="{ active: parent == 'company' }">Company</router-link>
          <router-link to="/settings/groups" :class="{ active: parent == 'groups' }">Groups</router-link>
          <router-link to="/settings/roles" :class="{ active: parent == 'roles' }">Roles</router-link>
          <router-link to="/settings/users" :class="{ active: parent == 'users' }">Users</router-link>
        </ul>
      </div>

      <!-- <hr /> -->
    </div>
  </div>
</template>

<script>
export default {
  name: "SettingMeta",
  data: () => ({
    parent: "companies"
  }),
  watch: {
    $route(newValue) {
      this.parent = newValue.path.split("/")[2];
    }
  }
};
</script>
