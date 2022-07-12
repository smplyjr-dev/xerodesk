<template>
  <aside>
    <div class="sidebar-toggler" @click="$emit('toggleSidebar', false)">
      <InlineSvg name="svg/mdi/close.svg" color="#4c5771" size="1.5rem" />
    </div>
    <div class="sidebar-logo">
      <router-link to="/" class="sidebar-logo--big">
        <img loading="lazy" class="object-contain" src="/images/logo-large-white.png" alt="Page Background" height="50px" />
      </router-link>
      <router-link to="/" class="sidebar-logo--small">
        <img loading="lazy" class="object-contain" src="/images/logo-small-white.png" alt="Page Background" height="50px" />
      </router-link>
    </div>
    <div class="sidebar-navs">
      <ul class="nav flex-column">
        <li v-for="(nav, navKey) in ready" :key="navKey">
          <template v-if="nav.child">
            <router-link :to="nav.to" data-toggle="collapse" :data-target="`#${nav.name.toLowerCase()}`" :aria-expanded="$route.fullPath.includes(nav.to)" :event="''">
              <div class="nav-icon"><InlineSvg :name="`svg/custom/${nav.icon}.svg`" color="#fff" size="1.5rem" /></div>
              <div class="nav-text">{{ nav.name }}</div>
            </router-link>

            <div :id="`${nav.name.toLowerCase()}`" class="collapse m-0 p-0" :class="{ show: $route.fullPath.includes(nav.to) }">
              <ul class="nav-child">
                <li class="nav-child-item" v-for="(child, childKey) in nav.child" :key="childKey">
                  <router-link class="nav-child-item-link" :to="child.to" @click.native="$emit('toggleSidebar', false)">{{ child.name }}</router-link>
                </li>
              </ul>
            </div>
          </template>

          <template v-else>
            <router-link @click.native="$emit('toggleSidebar', false)" :to="nav.to">
              <div class="nav-icon"><InlineSvg :name="`svg/custom/${nav.icon}.svg`" color="#fff" size="1.5rem" /></div>
              <div class="nav-text">{{ nav.name }}</div>
            </router-link>
          </template>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
export default {
  props: ["isOpen"],
  name: "Sidebar",
  data() {
    let isDevelopment = window.Laravel.APP_ENV == "local" ? 1 : 0;

    return {
      // prettier-ignore
      navs: [
        { name: "Dashboard", to: "/dashboard", ready: isDevelopment, icon: "home",                    },
        { name: "Mail",      to: "/mail",      ready: isDevelopment, icon: "envelope",                },
        { name: "Tickets",   to: "/tickets",   ready: isDevelopment, icon: "mdi/ticket-confirmation", },
        { name: "Live Chat", to: "/chats",     ready: true,          icon: "forum",                   },
        { name: "Clients",   to: "/clients",   ready: true,          icon: "clients",                 },
        { name: "Report",    to: "/reports",   ready: isDevelopment, icon: "report",                  },
        { name: "Settings",  to: "/settings",  ready: true,          icon: "settings",                },
      ]
    };
  },
  computed: {
    ready() {
      return this.navs.filter((n) => n.ready);
    }
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
