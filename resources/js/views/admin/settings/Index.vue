<template>
  <div class="container-fluid px-4 mt-4">
    <div class="page-title">
      <h4 class="mb-2">Settings</h4>
    </div>

    <h4>Team</h4>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex, recusandae?</p>

    <div class="row">
      <div class="col-sm-4 my-2">
        <div class="card">
          <div class="card-header">Users</div>
          <div class="card-body">Create, update or delete a user from your app.</div>
        </div>
      </div>
      <div class="col-sm-4 my-2">
        <div class="card">
          <div class="card-header">Groups</div>
          <div class="card-body">Organize your users by setting them to a group.</div>
        </div>
      </div>
      <div class="col-sm-4 my-2">
        <div class="card">
          <div class="card-header">Roles</div>
          <div class="card-body">Provide and restrict levels of access to your users.</div>
        </div>
      </div>
    </div>

    <br />
    <br />

    <h4>Account</h4>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex, recusandae?</p>

    <div class="row">
      <div class="col-sm-4 my-2">
        <div class="card">
          <div class="card-header">Company</div>
          <div class="card-body">Configure your app to add finish it's identity.</div>
        </div>
      </div>
      <div class="col-sm-4 my-2">
        <div class="card">
          <div class="card-header">Script</div>
          <div class="card-body">Copy and paste the script from here to your web app.</div>
        </div>
      </div>
      <div class="col-sm-4 my-2">
        <div class="card">
          <div class="card-header">SLA Policies</div>
          <div class="card-body">Setup targets to guarantee timely responses and solutions to customers.</div>
        </div>
      </div>
    </div>

    <br />
    <br />

    <h4>Developer</h4>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex, recusandae?</p>

    <div class="row">
      <div class="col-sm-4 my-2">
        <div class="card">
          <div class="card-header">Web Notification</div>
          <div class="card-body">Configure your default Operating System notification here.</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "Admin",
  name: "Dashboard",
  metaInfo: () => ({ title: "Dashboard" }),
  middleware: ["auth"]
};
</script>
