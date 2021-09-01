function page(path) {
  return () => import(/* webpackChunkName: '' */ `@Scripts/views/admin/${path}`).then(m => m.default || m);
}

export default [
  // Admin Routes
  { path: "/dashboard", component: page("Dashboard.vue") },
  { path: "/profile",   component: page("Profile.vue") },
  { path: "/reports",   component: page("reports/Index.vue") },

  // Ticket Routes
  { path: "/tickets",          component: page("ticket/Index.vue") },
  { path: "/tickets/:session", component: page("ticket/Show.vue") },

  // Ticket Routes
  { path: "/clients",     component: page("client/Index.vue") },
  { path: "/clients/:id", component: page("client/Show.vue") },

  // Setting Routes
  { path: "/settings/company",        component: page("settings/Company.vue") },
  { path: "/settings/groups",         component: page("settings/groups/Index.vue") },
  { path: "/settings/users",          component: page("settings/users/Index.vue") },
  { path: "/settings/users/create",   component: page("settings/users/Create.vue") },
  { path: "/settings/users/:id/edit", component: page("settings/users/Edit.vue") },
  { path: "/settings/roles",          component: page("settings/roles/Index.vue") },
  { path: "/settings/slas",           component: page("settings/slas/Index.vue") },

  // Developer Routes (for testing purposes only)
  // { path: "/developers/webnotif", component: page("developer/WebNotif.vue") }
];
