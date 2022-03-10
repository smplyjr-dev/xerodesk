<template>
  <nav class="navbar navbar-expand-lg py-3 px-4">
    <div class="mr-auto">
      <div class="burger" @click="$emit('toggle-sidebar', !isOpen)">
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
                <img class="rounded-circle object-cover" :src="$profilePicture(user)" @error="$onImgError($event, 1)" alt="Profile Picture" height="37px" width="37px" />
              </a>
            </template>

            <dropdown-content>
              <template v-slot:content>
                <!-- <dropdown-item class="cdci--unstyled">
                  <router-link class="link-unstyled d-block" to="/profile">
                    <p class="mx-2 mb-0 font-weight-bold">{{ `${user.bio.first_name} ${user.bio.last_name}` }}</p>
                    <p class="mx-2 mb-2 text-xs text-secondary">{{ user.role }}</p>
                  </router-link>
                </dropdown-item> -->
                <div class="my-2">
                  <p class="mx-2 mb-0 font-weight-bold">{{ `${user.bio.first_name} ${user.bio.last_name}` }}</p>
                  <p class="mx-2 mb-2 text-xs text-secondary">{{ user.role }}</p>
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
