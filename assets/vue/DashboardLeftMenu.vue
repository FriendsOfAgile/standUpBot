<template>
    <main class="w-full flex">
        <div class="px-6 py-4 h-fullVh shadow-xl space-y-4 flex flex-col items-center">
            <router-link to="/">
                <img src="/images/logo.png" class="pb-8" alt="logo"/>
            </router-link>
            <router-link to="/dashboard/standup/">
                <font-awesome-icon icon="user-secret"/>
            </router-link>
            <span>123</span>
            <span>123</span>
        </div>
    </main>
</template>

<script>
  export default {
    name: "DashboardLeftMenu"
  }
</script>

<style scoped>

</style>
