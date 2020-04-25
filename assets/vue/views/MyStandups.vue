<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">My standups</h3>
        </div>
        <div class="w-full grid grid-cols-3 gap-4 p-6">
            <div class="p-6 shadow p-4" v-for="standup in currentStandUps">
                <h3 class="font-bold text-xxl pb-4">{{ standup.name }}</h3>
                <div class="text-gray-500">Intro message: <span class="text-gray-800">{{ standup.messageBefore }}</span></div>
                <div class="text-gray-500">Outro message: <span class="text-gray-800">{{ standup.messageAfter }}</span></div>
                <div class="text-gray-500">Members: <span class="text-gray-800">{{ standup.members.length }}</span></div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "ManageStandup",
    data() {
      return {
      }
    },
    computed: {
      currentStandUps() {
        return this.$store.getters.getStandUpConfigs;
      }
    },
  }
</script>

<style scoped>

</style>
