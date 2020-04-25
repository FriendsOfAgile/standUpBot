<template>
    <div class="w-full flex p-6 flex-col">
        <div class="flex">
            <h3 class="font-bold text-2xl text-gray-700">Dashboard</h3>
        </div>
        <transition name="slideRight" mode="out-in">
            <div class="w-full mt-6" v-if="getCurrentStandUps">
                <h3 class="text-xl text-gray-500">
                    Active standups: <span class="text-gray-700"> {{ getCurrentStandUps }} </span>
                </h3>
            </div>
        </transition>

    </div>

</template>

<script>

import DashboardLeftMenu from "../components/DashboardLeftMenu.vue";
export default {
  name: 'Home',
  components: {
    DashboardLeftMenu
  },
  data() {
    return {
      rendered: false
    }
  },
  computed: {
    getCurrentStandUps() {
      return this.$store.getters.getStandUpConfigs.length;
    }
  },
  mounted() {
  }
}
</script>
