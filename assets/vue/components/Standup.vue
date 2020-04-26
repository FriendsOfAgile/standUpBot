<template>
    <div class="w-full flex flex-col">
        <div class="flex px-6 pt-6">
            <h3 class="font-bold text-2xl text-gray-700">Standup {{ standUpData.name }}</h3>
        </div>
        <div class="flex flex-col p-6">
            <div class="flex flex-col space-y-4">
                <label for="beforeMessage" class="text-gray-500">Intro message:
                    <input id="beforeMessage" class="w-full text-gray-700 border border-gray-500 rounded mt-2 p-3" autofocus type="text" v-model="standUpData.messageBefore">
                </label>
                <label for="afterMessage" class="text-gray-500">Outro message:
                    <input id="afterMessage" class="w-full text-gray-700 border border-gray-500 rounded mt-2 p-3" type="text" v-model="standUpData.messageAfter">
                </label>
            </div>
            <div class="flex flex-col p-1 mt-2">
                <h3 class="text-lg font-bold text-gray-700 border-b-4 mt-4 mb-2 border-accentColor w-content">Questions</h3>
                <div class="flex flex-col ">
                    <div class="w-full flex-flex-col" v-for="question in standUpData.questions">
                        <div class="border-l-4 py-1 px-2 ml-4 mt-4 " :style="{'border-color': question.color}">
                            {{ question.text }}
                        </div>
                    </div>
                </div>

            </div>
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
