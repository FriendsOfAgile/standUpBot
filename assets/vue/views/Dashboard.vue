<template>
    <div class="w-full flex flex-col">
        <div class="flex px-6 pt-6">
            <h3 class="font-bold text-2xl text-gray-700">Dashboard</h3>
        </div>
        <div class="w-full p-6">
            <router-link to="/dashboard/standups">
                <h3 class="text-xl text-gray-500">
                    Standup configs: <span class="text-gray-700"> {{ getCurrentStandUps }} </span>
                </h3>
            </router-link>
        </div>
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
      }
    },
    computed: {
      getCurrentStandUps() {
        return this.$store.getters.getStandUpConfigs.length ? this.$store.getters.getStandUpConfigs.length : "0";
      }
    },
    mounted() {
    },
  }
</script>
