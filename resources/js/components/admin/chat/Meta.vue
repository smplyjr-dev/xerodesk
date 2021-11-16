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
        <!-- <img src="https://decoholic.org/wp-content/uploads/2013/02/minimalism_22_living_room.jpg" class="object-cover w-100" height="65px" style="border-radius: 8px;" /> -->
        <!-- <img src="https://s-media-cache-ak0.pinimg.com/originals/95/fa/0e/95fa0e315cf80554b784946ac5ea583c.jpg" class="object-cover w-100" height="65px" style="border-radius: 8px;" /> -->
        <!-- <img src="https://i.pinimg.com/originals/00/ef/dd/00efdd1ec996450fecea6fd19edf6b22.png" class="object-cover w-100" height="65px" style="border-radius: 8px;" /> -->
        <!-- <img src="https://ae01.alicdn.com/kf/HTB1Oo2bphuTBuNkHFNRq6A9qpXaz/Living-Room-Sofa-set-minimalist-modern-sofa-real-genuine-cow-leather-sectional-sofa-muebles-de-sala.jpg" class="object-cover w-100" height="65px" style="border-radius: 8px;" /> -->
        <!-- <img src="https://www.digsdigs.com/photos/Modern-minimalist-living-room-designs-by-MobilFresno-23.jpg" class="object-cover w-100" height="65px" style="border-radius: 8px;" /> -->
        <!-- <img src="https://i.pinimg.com/originals/0a/e1/8f/0ae18fb1236467fe18b8290d890473c7.jpg" class="object-cover w-100" height="65px" style="border-radius: 8px;" /> -->
        <img src="https://via.placeholder.com/200x100.png?text=Holder" class="object-cover w-100" height="65px" style="border-radius: 8px;" />
        <img src="https://via.placeholder.com/200x100.png?text=Holder" class="object-cover w-100" height="65px" style="border-radius: 8px;" />
        <img src="https://via.placeholder.com/200x100.png?text=Holder" class="object-cover w-100" height="65px" style="border-radius: 8px;" />
        <img src="https://via.placeholder.com/200x100.png?text=Holder" class="object-cover w-100" height="65px" style="border-radius: 8px;" />
        <img src="https://via.placeholder.com/200x100.png?text=Holder" class="object-cover w-100" height="65px" style="border-radius: 8px;" />
        <img src="https://via.placeholder.com/200x100.png?text=Holder" class="object-cover w-100" height="65px" style="border-radius: 8px;" />
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
