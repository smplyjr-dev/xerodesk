<template>
  <nav class="navbar navbar-expand-lg py-3 px-4">
    <div class="mr-auto">
      <div class="burger" @click="$emit('toggleSidebar', !isOpen)">
        <div class="burger--item"></div>
        <div class="burger--item"></div>
        <div class="burger--item"></div>
      </div>
    </div>

    <div class="ml-auto">
      <ul class="nav">
        <li class="nav-item">
          <dropdown :carret="false" position="right">
            <template v-slot:value>
              <a href="javascript:void(0)" class="nav-link p-0">
                <div class="nav-dropdown">
                  <i class="fas fa-caret-down"></i>
                </div>
              </a>
            </template>

            <dropdown-content>
              <template v-slot:content>
                <div class="flex-center-center mb-3">
                  <img class="rounded-circle object-cover" :src="$profilePicture(user)" @error="$onImgError($event, 1)" alt="Profile Picture" height="45px" width="45px" />
                  <div style="line-height: 1rem">
                    <p class="mx-2 mb-0 font-weight-bold">{{ `${user.bio.first_name} ${user.bio.last_name}` }}</p>
                    <p class="mx-2 mb-0 text-xs text-secondary">{{ user.email }}</p>
                  </div>
                </div>
                <hr class="my-1" />
                <dropdown-item>
                  <router-link class="link-unstyled d-block" to="/settings/account/profile">Profile Settings</router-link>
                </dropdown-item>
                <dropdown-item @select="logout()">Logout</dropdown-item>
              </template>
            </dropdown-content>
          </dropdown>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: ["isOpen"],
  name: "Navbar",
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    async logout() {
      // Log out the user
      await this.$store.dispatch("auth/logout");

      // Redirect to auth using native JavaScript
      window.location.href = "/";
    }
  }
};
</script>
