function page(path) {
  return () => import(/* webpackChunkName: '' */ `@Scripts/views/neutral/${path}`).then(m => m.default || m);
}

export default [
  // Authentication Routes
  { path: "/",                      component: page("Login.vue") },
  { path: "/register",              component: page("Register.vue") },
  { path: "/password/reset",        component: page("password/Request.vue") },
  { path: "/password/reset/:token", component: page("password/Reset.vue") },
  { path: "/email/verify/:id",      component: page("verification/Verify.vue") },
  { path: "/email/resend",          component: page("verification/Resend.vue") },
  { path: "*",                      component: page("NotFound.vue") }
];
