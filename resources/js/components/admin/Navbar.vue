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
          <app-dropdown :carret="false" position="right">
            <template v-slot:value>
              <a href="javascript:void(0)" class="nav-link p-0">
                <img class="rounded-circle object-cover" :src="profilePicture" @error="$onImgError($event, 1)" alt="Profile Picture" height="37px" width="37px" />
              </a>
            </template>

            <app-dropdown-content>
              <template v-slot:content>
                <p class="mx-2 mb-0 font-weight-bold">{{ `${user.bio.first_name} ${user.bio.last_name}` }}</p>
                <p class="mx-2 mb-2 text-xs text-secondary">{{ user.role }}</p>
                <hr class="my-1" />
                <app-dropdown-item @select="$router.push('/profile')">Profile</app-dropdown-item>
                <hr class="my-1" />
                <app-dropdown-item @select="logout()">Logout</app-dropdown-item>
              </template>
            </app-dropdown-content>
          </app-dropdown>
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
    ...mapState("auth", ["user"]),

    profilePicture() {
      if (this.user.profile_picture == "generic-profile.png") {
        return `https://ui-avatars.com/api/?font-size=0.35&name=${this.user.bio.first_name}`;
      } else {
        return `${this.$APP_URL}/storage/uploads/users/${this.user.id}/${this.user.profile_picture}`;
      }
    }
  },
  methods: {
    async logout() {
      // Clear all the notifications
      // this.$store.commit("notifications/CLEAR_NOTIFICATIONS");

      // Log out the user
      await this.$store.dispatch("auth/logout");

      // Redirect to auth using native JavaScript
      window.location.href = "/";
    }
  }
};
</script>
