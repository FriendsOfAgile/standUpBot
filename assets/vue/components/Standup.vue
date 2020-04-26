<template>
    <div class="w-full flex flex-col">
        <div class="flex p-6">
            <h3 class="font-bold text-2xl text-gray-700">Standup {{ standUpData.name }}</h3>
        </div>
        <div class="flex flex-col p-6">
            <div class="text-gray-500">Intro message:
                <span class="text-gray-700" v-if="editFieldShown !== 'messageBefore'">{{ standUpData.messageBefore }}</span>
                <font-awesome-icon :icon="'edit'" v-if="editFieldShown !== 'messageBefore'" class="ml-2 edit-field-icon" @click="editField('messageBefore')"/>
                <input class="text-gray-700 border border-gray-500 rounded ml-2 px-2" autofocus type="text" v-if="editFieldShown === 'messageBefore'" v-model="standUpData.messageBefore" @keyup.enter="editFieldShown = ''" @focusout="editFieldShown = ''">
            </div>
            <div class="text-gray-500">Outro message: <span class="text-gray-700">{{ standUpData.messageAfter }}</span></div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "Standup",
    data() {
      return {
        standUpData: {},
        editFieldShown: "",
        edited: false,
      }
    },
    methods: {
      editField(fieldRef) {
        this.editFieldShown = fieldRef;
      }
    },
    mounted() {
      console.log(this.$route.params.id);
      if(this.$store.getters.getStandUpConfigs.length) {
        this.standUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
      } else {
        this.$store.dispatch('GET_STANDUP_CONFIGS').then( () => {
          this.standUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
        })
      }
    },
    watch: {
        standUpData: {
          handler(newValue, oldValue) {
            if(newValue !== oldValue) {
              this.edited = true;
            }
          },
          deep: true
        }
    }
  }
</script>

<style scoped>
    .edit-field-icon {
        color: #7e91ff;
    }
</style>
