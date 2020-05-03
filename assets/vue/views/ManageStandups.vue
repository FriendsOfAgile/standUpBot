<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6 justify-between items-center">
            <h3 class="font-bold text-2xl text-gray-700">Manage standups</h3>
            <router-link to="/dashboard/standups/new">
                <button class="bg-accentColor text-white border-2 border-accentColor font-bold py-2 px-4 rounded focus:outline-none">
                <span>
                    <font-awesome-icon icon="plus" class="mr-1"/>
                    New config
                </span>
                </button>
            </router-link>
        </div>

            <div class="w-full grid grid-cols-3 gap-4 p-6" v-if="currentStandUps.length">
                <div class="p-6 shadow p-4 transition duration-500 ease-in-out hover:shadow-lg" v-for="standup in currentStandUps">
                    <router-link :to="{ name: 'Standup',  params: { id: standup.id } }">
                        <div class="flex w-full justify-between">
                            <h3 class="font-bold text-xxl pb-4">{{ standup.name }}</h3>
                            <div class="text-red-600 text-xs" @click.prevent @click="showDeleteConfirmationModal(standup)">delete</div>
                        </div>
                        <div class="text-gray-500">Intro message: <span class="text-gray-800">{{ standup.messageBefore }}</span></div>
                        <div class="text-gray-500">Outro message: <span class="text-gray-800">{{ standup.messageAfter }}</span></div>
                        <div class="text-gray-500">Questions: <span class="text-gray-800">{{ standup.questions.length }}</span></div>
                        <div class="text-gray-500">Members: <span class="text-gray-800">{{ standup.members.length }}</span></div>
                    </router-link>
                </div>
            </div>
            <div class="w-full p-6" v-else>
                <p class="text-lg">You have no configs yet.<router-link to="/dashboard/standups/new" class="font-bold text-accentColor"> Create one now!</router-link></p>
            </div>

        <modal name="confirm-standup-deletion" width="30%" height="auto" class="shadow-lg">
            <div class="w-full flex flex-col p-4 items-center">
                <h3 class="text-xl text-center">Are you sure want to delete <span class="text-2xl font-bold">{{ standUpDataPassed.name}}?</span></h3>
                <p class="mt-4">We won't be able to recover your data.</p>
                <button class="mt-8 bg-red-400 py-2 px-4 rounded text-white font-bold hover:bg-red-700" @click="deleteStandUpConfig(standUpDataPassed.id)">Delete</button>
            </div>
        </modal>
    </div>
</template>

<script>
  export default {
    name: "ManageStandup",
    data() {
      return {
        standUpDataPassed: {},
      }
    },
    methods: {
      showDeleteConfirmationModal(data) {
        this.standUpDataPassed = data;
        this.$modal.show('confirm-standup-deletion')
      },
      deleteStandUpConfig(id) {
        this.$loading(true);
        return this.$store.dispatch("DELETE_STANDUP_CONFIG", id).then( () => {
          this.$modal.hide('confirm-standup-deletion');
          this.$loading(false);
        })
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
