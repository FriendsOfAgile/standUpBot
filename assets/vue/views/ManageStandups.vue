<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">Manage standups</h3>
        </div>
        <div class="w-full grid grid-cols-3 gap-4 p-6">
            <div class="p-6 shadow p-4 transition duration-500 ease-in-out hover:shadow-lg" v-for="standup in currentStandUps">
                <router-link :to="{ name: 'Standup',  params: { id: standup.id } }">
                    <h3 class="font-bold text-xxl pb-4">{{ standup.name }}</h3>
                    <div class="text-gray-500">Intro message: <span class="text-gray-800">{{ standup.messageBefore }}</span></div>
                    <div class="text-gray-500">Outro message: <span class="text-gray-800">{{ standup.messageAfter }}</span></div>
                    <div class="text-gray-500">Questions: <span class="text-gray-800">{{ standup.questions.length }}</span></div>
                    <div class="text-gray-500">Members: <span class="text-gray-800">{{ standup.members.length }}</span></div>
                </router-link>
                <button class="p-4 bg-red-400" @click="showDeleteConfirmationModal">Delete</button>
            </div>
        </div>

        <modal name="confirm-standup-deletion" width="30%" height="40%">

            <button @click="deleteStandUpConfig">agree!</button>
        </modal>
    </div>
</template>

<script>
  export default {
    name: "ManageStandup",
    data() {
      return {
      }
    },
    methods: {
      showDeleteConfirmationModal() {
        this.$modal.show('confirm-standup-deletion')
      },
      deleteStandUpConfig() {
        alert('ok!');
        this.$modal.hide('confirm-standup-deletion');
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
