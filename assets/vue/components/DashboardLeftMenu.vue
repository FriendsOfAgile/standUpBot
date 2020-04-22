<template>
    <div class="px-6 py-4 h-fullVh shadow-xl space-y-6 flex flex-col items-center">
        <router-link to="/">
            <img src="/images/logo.png" class="pb-6" alt="logo"/>
        </router-link>
        <router-link  class="relative" to="/dashboard/standup/" @mouseenter.native="showNavTooltip('manageStandup')" @mouseleave.native="activeTooltip = undefined">
            <font-awesome-icon icon="cogs" class="text-gray-600 text-xl"/>
            <div class="bg-white absolute top-0 nav-item__tooltip text-sm border border-gray-400 rounded p-2" v-if="activeTooltip === 'manageStandup'">{{ navTooltips[activeTooltip] }}</div>
        </router-link>
        <router-link  class="relative" to="/dashboard/reports/" @mouseenter.native="showNavTooltip('reports')" @mouseleave.native="activeTooltip = undefined">
            <font-awesome-icon icon="chart-pie" class="text-gray-600 text-xl"/>
            <div class="bg-white absolute top-0 nav-item__tooltip text-sm border border-gray-400 rounded p-2" v-if="activeTooltip === 'reports'">{{ navTooltips[activeTooltip] }}</div>
        </router-link>
    </div>
</template>

<script>
  export default {
    name: "DashboardLeftMenu",
    data() {
      return {
        navTooltips:
          {
            'manageStandup': 'Manage standups',
            'reports': 'Reports'
          },
        activeTooltip: undefined,

      }
    },
    methods: {
      showNavTooltip(menuItem) {
        this.activeTooltip = menuItem;
      }
    }
  }
</script>

<style scoped>
    .nav-item__tooltip {
        left: 2.5em;
        width: max-content;
    }
</style>
