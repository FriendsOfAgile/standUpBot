<template>
    <div class="w-full h-fullVh flex justify-center items-center">
        <div class="flex flex-col text-center">
            <h3 class="text-xl text-gray-500 font-bold">Get started</h3>
            <div class="w-full flex mt-4 space-x-6">
                <a href="/connect/slack">
                    <img class="mt-2 cursor-pointer" src="/images/slack-logo.png" />
                </a>
                <img class="mt-2 cursor-pointer" src="/images/ms-teams.png" />
                <img class="mt-2 cursor-pointer" src="/images/discord-logo.png" />
            </div>

        </div>
    </div>
</template>

<script>
  export default {
    name: "Login",
    data() {
      return {

      }
    },
    methods: {
    }
  }
</script>

<style scoped>

</style>
