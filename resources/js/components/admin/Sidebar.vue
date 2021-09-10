<template>
  <aside class="h-100">
    <ul class="nav flex-column">
      <li class="nav-item" v-for="(nav, navKey) in navs" :key="navKey">
        <template v-if="nav.child">
          <router-link class="nav-link" :to="nav.to" data-toggle="collapse" :data-target="`#${nav.name.toLowerCase()}`" :aria-expanded="$route.fullPath.includes(nav.to)" :event="''">
            <InlineSvg :name="nav.icon" size="1.5rem" />
            <span>{{ nav.name }}</span>
            <InlineSvg class="dropdown-icon" name="svg/chevron/down.svg" size=".75rem" />
          </router-link>

          <div :id="`${nav.name.toLowerCase()}`" class="collapse m-0 p-0" :class="{ show: $route.fullPath.includes(nav.to) }">
            <ul class="nav-child">
              <li class="nav-child-item" v-for="(child, childKey) in nav.child" :key="childKey">
                <router-link class="nav-child-item-link" :to="child.to" @click.native="$emit('toggle-sidebar', false)">{{ child.name }}</router-link>
              </li>
            </ul>
          </div>
        </template>

        <template v-else>
          <router-link class="nav-link" :to="nav.to" @click.native="$emit('toggle-sidebar', false)">
            <InlineSvg :name="nav.icon" size="1.5rem" />
            <span>{{ nav.name }}</span>
          </router-link>
        </template>
      </li>
    </ul>
  </aside>
</template>

<script>
export default {
  props: ["isOpen"],
  name: "Sidebar",
  data: () => ({
    navs: [
      // {
      //   name: "Dashboard",
      //   to: "/dashboard",
      //   icon: "template/mdi-dashboard.svg"
      // },
      {
        name: "Ticket",
        to: "/tickets",
        icon: "template/mdi-ticket-confirmation.svg"
      },
      {
        name: "Client",
        to: "/clients",
        icon: "template/mdi-account-group.svg"
      },
      // {
      //   name: "Report",
      //   to: "/reports",
      //   icon: "template/mdi-file-chart.svg"
      // },
      {
        name: "Settings",
        to: "/settings",
        icon: "template/mdi-cog.svg",
        child: [
          {
            name: "SLA",
            to: "/settings/slas"
          },
          {
            name: "Company",
            to: "/settings/company"
          },
          {
            name: "Groups",
            to: "/settings/groups"
          },
          {
            name: "Roles",
            to: "/settings/roles"
          },
          {
            name: "Users",
            to: "/settings/users"
          }
        ]
      }
      /* {
        name: "Developers",
        to: "/developers",
        icon: "template/mdi-code.svg",
        child: [
          {
            name: "Web Notification",
            to: "/developers/webnotif"
          }
        ]
      } */
    ]
  })
};
</script>
