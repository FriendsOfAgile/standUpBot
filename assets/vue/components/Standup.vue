<template>
    <div class="w-full flex flex-col">
        {{ standUpData.questions }}
        <div class="flex justify-between px-6 pt-6">
            <h3 class="font-bold text-2xl text-gray-700">Standup {{ standUpData.name }}</h3>
            <button class="bg-white text-white border-2 border-gray-500 font-bold py-2 px-4 rounded" :class="[!edited ? 'cursor-not-allowed' : '', !edited ? 'opacity-50' : '', !edited ? 'text-gray-500' : '', edited ? 'bg-accentColor' : '', edited ? 'border-accentColor' : '' ]">
                <span v-if="!edited">
                    <font-awesome-icon icon="check" class="mr-1"/>
                    Config saved
                </span>
                <span v-else>
                      <font-awesome-icon icon="save" class="mr-1"/>
                       Save changes
                </span>
            </button>
        </div>
        <div class="flex flex-col p-6">
            <div class="flex flex-col space-y-4">
                <label for="beforeMessage" class="text-gray-500">Intro message:
                    <input id="beforeMessage" class="w-full text-gray-700 border border-gray-500 rounded mt-2 p-3" autofocus type="text" v-model="standUpData.messageBefore" @input="compareConfigs">
                </label>
                <label for="afterMessage" class="text-gray-500">Outro message:
                    <input id="afterMessage" class="w-full text-gray-700 border border-gray-500 rounded mt-2 p-3" type="text" v-model="standUpData.messageAfter" @input="compareConfigs">
                </label>
            </div>
            <div class="flex flex-col p-1 mt-2">
                <h3 class="text-lg font-bold text-gray-700 border-b-4 mt-4 mb-2 border-accentColor w-content">Questions</h3>
                <div class="flex flex-col ">
                    <div class="w-full flex-flex-col" v-for="question in standUpData.questions" :key="question.id">
                        <div class="border-l-4 py-1 px-2 ml-4 mt-4 " :style="{'border-color': question.color}">
                            <input type="text" v-model="question.text" @input="compareConfigs"/>
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
        initialStandUpData: {},
        standUpData: {},
        editFieldShown: "",
        edited: false,
      }
    },
    methods: {
      compareConfigs() {
        if(JSON.stringify(this.standUpData) !== JSON.stringify(this.initialStandUpData)) {
          this.edited = true;
        } else {
          this.edited = false;
        }
      },
      editField(fieldRef) {
        this.editFieldShown = fieldRef;
      }
    },
    mounted() {
      if(this.$store.getters.getStandUpConfigs.length) {
        this.initialStandUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
        this.standUpData = {...this.initialStandUpData};
        //this.standUpData.questions.flat();
      } else {
        this.$store.dispatch('GET_STANDUP_CONFIGS').then( () => {
          this.initialStandUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
          this.standUpData = {...this.initialStandUpData};
          //this.standUpData.questions.flat();
        });
      }
    },
  }
</script>

<style scoped>
    .edit-field-icon {
        color: #7e91ff;
    }
</style>
