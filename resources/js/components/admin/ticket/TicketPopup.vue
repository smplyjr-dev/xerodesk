<template>
  <popup :arrow="true" offset="50px,5px" placement="left" toggle="hide" trigger="click">
    <div class="text-left text-break p-2" style="width: 250px;" slot="content">
      <div class="d-flex flex-column mb-2">
        <span class="font-weight-bold text-lg">{{ p.client }}</span>
        <span>{{ p.client_email }}</span>
      </div>
      <hr />
      <div class="d-flex flex-column mb-2">
        <span class="font-weight-bold">Ticket Title</span>
        <span>{{ p.title || "Not Set" }}</span>
      </div>
      <div class="d-flex flex-column mb-2">
        <span class="font-weight-bold">Ticket Service Category</span>
        <span>{{ p.category || "Not Set" }}</span>
      </div>
      <div class="d-flex flex-column mb-2">
        <span class="font-weight-bold">Ticket Resolution Code</span>
        <span>{{ p.resolution_code || "Not Set" }}</span>
      </div>
      <div class="d-flex flex-column mb-2">
        <span class="font-weight-bold">Ticket Solution</span>
        <span>{{ p.solution || "Not Set" }}</span>
      </div>
      <!-- <div class="d-flex flex-column mb-2">
        <span class="font-weight-bold">Ticket Tags</span>
        <span>{{ p.tags || "Not Set" }}</span>
      </div>
      <div class="d-flex flex-column mb-0">
        <span class="font-weight-bold">Ticket Timestamp</span>
        <span>{{ $dayjs("format", p.created_at, "MM/DD/YYYY hh:mm A") }}</span>
      </div> -->
    </div>

    <i class="fa fa-info-circle text-info cursor-pointer" slot="reference"></i>
  </popup>
</template>

<script>
export default {
  props: ["p"]
};
</script>
