<template>
  <q-page class="flex flex-center">
      <img
      alt="Quasar logo"
      src="~assets/werkenbij.png"
      id="homeImg"
      >
  </q-page>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'PageIndex',
  mounted() {
    this.setRightDrawerVisible(false)
    this.$q.notify({
      message: 'Please navigate to the Invoice System from menu',
      position: 'top',
      color: 'positive',
      timeout: 2500
    })
  },
  methods: {
    ...mapActions(["setRightDrawerVisible"])
  }
}
</script>

<style>
#homeImg {
    width: 400px;
    height: auto;
}
</style>