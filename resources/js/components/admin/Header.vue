<template>
  <header>
    <aside>
      <router-link class="sb-brand" to="/tickets">
        <img class="d-block d-lg-none object-contain" loading="lazy" :src="`${$APP_URL}/images/icon.png`" height="35px" alt="FiliPay Logo" />
        <img class="d-none d-lg-block object-contain" loading="lazy" :src="`${$APP_URL}/images/logo.png`" height="35px" alt="FiliPay Logo" />
      </router-link>

      <div class="sb-toggler">
        <button class="btn btn-default" @click="$emit('toggle-sidebar', !isOpen)">
          <InlineSvg name="template/mdi-sort-variant.svg" size="1.5rem" />
        </button>
      </div>
    </aside>

    <nav class="navbar navbar-expand-lg navbar-light">
      <ul class="nav ml-auto d-flex d-lg-none">
        <li class="nav-item dropdown" v-if="!$isNull(user)">
          <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ user.bio.first_name }}
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <router-link to="/profile" class="dropdown-item">Profile</router-link>
            <div class="dropdown-divider"></div>
            <a href="javascript:void(0)" class="dropdown-item" @click.prevent="logout()">
              Logout
            </a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" @click="$emit('toggle-sidebar', !isOpen)">
            <InlineSvg name="template/mdi-sort-variant.svg" />
          </a>
        </li>
      </ul>

      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown" v-if="!$isNull(user)">
            <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ user.bio.first_name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right">
              <router-link to="/profile" class="dropdown-item">Profile</router-link>
              <div class="dropdown-divider"></div>
              <a href="javascript:void(0)" class="dropdown-item" @click.prevent="logout()">
                Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: ["isOpen"],
  name: "Header",
  computed: {
    ...mapState("auth", ["user"]),

    profilePicture() {
      if (this.user.profile_picture == "generic-profile.png") {
        return `${this.$APP_URL}/images/${this.user.profile_picture}`;
      } else {
        return `${this.$APP_URL}/storage/uploads/users/${this.user.id}/${this.user.profile_picture}`;
      }
    }
  },
  methods: {
    async logout() {
      // Clear all the notifications
      this.$store.commit("notifications/CLEAR_NOTIFICATIONS");

      // Log out the user
      await this.$store.dispatch("auth/logout");

      // Redirect to auth using native JavaScript
      window.location.href = "/";
    }
  }
};
</script>
