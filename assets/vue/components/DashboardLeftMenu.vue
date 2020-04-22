<template>
    <div class="px-6 py-4 h-fullVh shadow-xl space-y-4 flex flex-col items-center">
        <router-link to="/">
            <img src="/images/logo.png" class="pb-8" alt="logo"/>
        </router-link>
        <router-link to="/dashboard/standup/">
            <font-awesome-icon icon="cogs" class="text-gray-600 text-xl"/>
        </router-link>
    </div>
</template>

<script>
  export default {
    name: "DashboardLeftMenu",
    data() {
      return {

      }
    },
  }
</script>

<style scoped>

</style>
