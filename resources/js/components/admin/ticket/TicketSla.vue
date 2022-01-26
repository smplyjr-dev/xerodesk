<template>
  <div class="badge badge-pill" :class="{ 'text-white': getSla.sla.color != 'yellow' }" :style="{ background: getSla.sla.color }">
    <span>{{ getElapsed() }} hour(s)</span>
  </div>
</template>

<script>
export default {
  props: ["p", "sla"],
  computed: {
    getSla() {
      let today = new Date();
      let created = new Date(this.p.created_at);
      let elapsed = this.getDiff(today, created);
      let choosen = { elapsed, prev: {}, sla: {} };
      let index = this.sla.findIndex(s => elapsed <= s.range);

      if (index != undefined) {
        choosen.sla = index != undefined ? this.sla[index] : this.sla[this.sla.length - 1];
        choosen.prev = index != undefined ? this.sla[index - 1] : this.sla[this.sla.length - 2];
      }

      if (index == -1) {
        choosen.sla = this.sla[this.sla.length - 1];
        choosen.prev = this.sla[this.sla.length - 1];
      }

      return choosen;
    }
  },
  methods: {
    getFormatted(n) {
      return `0${(n / 60) ^ 0}`.slice(-2) + ":" + ("0" + (n % 60)).slice(-2);
    },
    getDiff(dt2, dt1) {
      let diff = (dt2.getTime() - dt1.getTime()) / 1000;
      diff /= 60;

      return Math.abs(Math.round(diff));
    },
    getElapsed() {
      let cloned = Object.assign({}, this.getSla);
      let elapsed = cloned.elapsed;
      let e = 0;

      if (cloned.prev != undefined) {
        e = elapsed - cloned.prev.range <= 0 ? elapsed : elapsed - cloned.prev.range;
      } else {
        e = elapsed;
      }

      return this.getFormatted(e);
    }
  }
};
</script>
