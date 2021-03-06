function page(path) {
  return () => import(/* webpackChunkName: '' */ `@Scripts/views/admin/${path}`).then(m => m.default || m);
}

export default [
  // Admin Routes
  { path: "/dashboard", component: page("Dashboard.vue") },
  { path: "/profile",   component: page("Profile.vue") },
  { path: "/reports",   component: page("reports/Index.vue") },
  
  // Email Routes
  { path: "/mail",       component: page("mail/Index.vue") },
  { path: "/mail/sent",  component: page("mail/Sent.vue") },
  { path: "/mail/draft", component: page("mail/Draft.vue") },

  // Ticket Routes
  { path: "/tickets",          component: page("ticket/Index.vue") },
  { path: "/tickets/:session", component: page("ticket/Show.vue") },

  // Live Chat Routes
  { path: "/chats",          component: page("chat/Index.vue") },
  { path: "/chats/:session", component: page("chat/Show.vue") },

  // Client Routes
  { path: "/clients",     component: page("client/Index.vue") },
  { path: "/clients/:id", component: page("client/Show.vue") },

  // Setting Routes
  { path: "/settings",                  component: page("settings/Index.vue") },
  { path: "/settings/account/photo",    component: page("settings/account/Photo.vue") },
  { path: "/settings/account/profile",  component: page("settings/account/Profile.vue") },
  { path: "/settings/account/password", component: page("settings/account/Password.vue") },
  { path: "/settings/account/replies",  component: page("settings/account/Replies.vue") },
  { path: "/settings/company",          component: page("settings/Company.vue") },
  { path: "/settings/groups",           component: page("settings/groups/Index.vue") },
  { path: "/settings/users",            component: page("settings/users/Index.vue") },
  { path: "/settings/users/:id/edit",   component: page("settings/users/Edit.vue") },
  { path: "/settings/roles",            component: page("settings/roles/Index.vue") },
  { path: "/settings/slas",             component: page("settings/slas/Index.vue") },
  { path: "/settings/widget/customize", component: page("settings/widget/Customize.vue") },
  { path: "/settings/widget/integrate", component: page("settings/widget/Integrate.vue") },

  // Developer Routes (for testing purposes only)
  { path: "/developers/webnotif", component: page("developer/WebNotif.vue") },
];
