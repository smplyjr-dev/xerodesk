<template>
  <div class="meta">
    <div class="meta-header">
      <span @click="$emit('toggle')">Back</span>
    </div>
    <div class="meta-content">
      <div style="display: grid; gap: 0.75rem; grid-template-columns: repeat(3, 1fr);">
        <button class="btn rounded-pill btn-sm btn-primary" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="Update Ticket">
          <i class="fa fa-fw fa-edit"></i>
        </button>
        <button class="btn rounded-pill btn-sm btn-danger" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="Transfer Ticket">
          <i class="fa fa-fw fa-exchange-alt"></i>
        </button>
        <button class="btn rounded-pill btn-sm btn-info" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="Send Transcript">
          <i class="fa fa-fw fa-envelope-open"></i>
        </button>
      </div>

      <hr />

      <p class="text-lg font-weight-bold text-center mt-4" style="color: #6588DE;">
        Client Details
      </p>

      <div class="d-flex justify-content-between w-100">
        <span>Client ID:</span>
        <span>Not Set</span>
      </div>
      <hr class="my-2" />
      <div class="d-flex justify-content-between w-100">
        <span>Client Token:</span>
        <span>Not Set</span>
      </div>
      <hr class="my-2" />
      <div class="d-flex justify-content-between w-100">
        <span>Client Email:</span>
        <span>Not Set</span>
      </div>
      <hr class="my-2" />
      <div class="d-flex justify-content-between w-100">
        <span>Client Phone:</span>
        <span>Not Set</span>
      </div>

      <hr />

      <p class="text-lg font-weight-bold text-center mt-4" style="color: #6588DE;">
        Uploaded Files
      </p>

      <div style="display: grid; gap: 0.75rem; grid-template-columns: repeat(3, 1fr);">
        <img class="object-cover w-100" height="65px" style="border-radius: 8px;" src="https://via.placeholder.com/200x100.png?text=Holder" />
        <img class="object-cover w-100" height="65px" style="border-radius: 8px;" src="https://via.placeholder.com/200x100.png?text=Holder" />
        <img class="object-cover w-100" height="65px" style="border-radius: 8px;" src="https://via.placeholder.com/200x100.png?text=Holder" />
        <img class="object-cover w-100" height="65px" style="border-radius: 8px;" src="https://via.placeholder.com/200x100.png?text=Holder" />
        <img class="object-cover w-100" height="65px" style="border-radius: 8px;" src="https://via.placeholder.com/200x100.png?text=Holder" />
        <img class="object-cover w-100" height="65px" style="border-radius: 8px;" src="https://via.placeholder.com/200x100.png?text=Holder" />
      </div>

      <hr />

      <p class="text-lg font-weight-bold text-center mt-4" style="color: #6588DE;">
        Ticket History
      </p>

      <ul class="timeline">
        <li v-for="h in histories" :key="h.id">
          <div class="time">{{ $dayjs("format", h.created_at, "MM/DD/YYYY h:mm A") }}</div>
          <div class="content" v-html="h.message"></div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    histories: [
      { id: 1, created_at: new Date(), message: "Lorem ipsum dolor sit amet <strong>consectetur</strong>." },
      { id: 2, created_at: new Date(), message: "Lorem ipsum dolor sit amet <strong>consectetur</strong>." }
    ]
  }),
  mounted() {
    $('[data-toggle="popover"]').popover();
  }
};
</script>
