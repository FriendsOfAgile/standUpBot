<template>
    <div class="w-full flex flex-col">
        <div class="flex justify-between px-6 pt-6">
            <h3 class="font-bold text-2xl text-gray-700">Standup {{ standUpData.name }}</h3>
            <button @click="updateStandUpConfig(standUpData)" class="bg-white text-white border-2 border-gray-500 font-bold py-2 px-4 rounded focus:outline-none" :class="[!edited ? 'cursor-not-allowed' : '', !edited ? 'opacity-50' : '', !edited ? 'text-gray-500' : '', edited ? 'bg-accentColor' : '', edited ? 'border-accentColor' : '' ]">
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
                    <input id="beforeMessage" class="focus:outline-none w-full text-gray-700 border border-gray-500 rounded mt-2 p-3" autofocus type="text" v-model="standUpData.messageBefore" @input="compareConfig">
                </label>
                <label for="afterMessage" class="text-gray-500">Outro message:
                    <input id="afterMessage" class="focus:outline-none w-full text-gray-700 border border-gray-500 rounded mt-2 p-3" type="text" v-model="standUpData.messageAfter" @input="compareConfig">
                </label>
            </div>
            <div class="flex flex-col p-1 mt-2">
                <h3 class="text-lg font-bold text-gray-700 border-b-4 mt-4 mb-2 border-accentColor w-content">Questions</h3>
                <div class="flex flex-col ">
                        <div class="w-full flex-flex-col" v-for="(question, index) in standUpData.questions" :key="question['@id']">
                            <div class="border-l-4 ml-4 mt-4 w-full flex items-center relative" :style="{'border-color': question.color}">
                                <input class="ml-1 py-1 px-2 focus:outline-none" type="text" v-model="question.text" @input="compareConfig"/>
<!--                             <input type="color" v-model="question.color" @change="compareConfig">-->
                                <div style="width: 24px; height: 24px;" :style="{'background-color': question.color}" class="rounded cursor-pointer" @click="showColorPicker = index"></div>
                                <transition name="fade">
                                    <div class="color-picker-container p-2 z-max" v-if="showColorPicker === index" @mouseleave="showColorPicker = false">
                                        <v-swatches v-model="question.color" @input="compareConfig(), showColorPicker = false" popover-y="up" inline="true"/>
                                    </div>
                                </transition>

                                <span class="text-red-600 text-xs ml-4 mt-1 cursor-pointer" @click="deleteQuestion(index)">delete</span>
                            </div>
                        </div>
                    <transition name="component-fade" mode="out-in">
                        <div class="border-l-4 ml-4 mt-4 w-full flex items-center relative" :style="{'border-color': newQuestion.color}" v-if="showNewQuestionInput" @keyup.enter="addQuestionToConfig">
                            <input class="ml-1 py-1 px-2 focus:outline-none" placeholder="Enter your question" ref="newQuestion" v-model="newQuestion.text" type="text" @input="compareConfig"/>
                            <div style="width: 24px; height: 24px;" :style="{'background-color': newQuestion.color}" class="rounded cursor-pointer" @click="showColorPicker = 'newQuestion'"></div>
                            <div class="color-picker-container p-2 z-max" v-if="showColorPicker === 'newQuestion'" @mouseleave="showColorPicker = false">
                                <v-swatches v-model="newQuestion.color" @input="showColorPicker = false" popover-y="up" inline="true"/>
                            </div>
                            <transition name="component-fade">
                                <span class="text-accentColor text-xs ml-4 mt-1 cursor-pointer" v-if="newQuestion.text.length" @click="addQuestionToConfig">save</span>
                            </transition>
                        </div>
                    </transition>
                    <div class="flex full mt-6 text-gray-700 items-center cursor-pointer" @click="focusNewQuestionInput">
                        <font-awesome-icon icon="plus"/>
                        <span class="ml-2">Add a question</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
  import VSwatches from 'vue-swatches'
  export default {
    name: "Standup",
    components: {
      VSwatches
    },
    data() {
      return {
        initialStandUpData: {},
        standUpData: {},
        editFieldShown: "",
        showColorPicker: false,
        showNewQuestionInput: false,
        newQuestion: {
          text: "",
          color: '#dddddd'
        },
        edited: false,
      }
    },
    methods: {
      compareConfig() {
        if(!this.lodash.isEqual(this.standUpData, this.initialStandUpData)) {
          this.edited = true;
        } else this.edited = false;
      },
      editField(fieldRef) {
        this.editFieldShown = fieldRef;
      },
      focusNewQuestionInput() {
        this.showNewQuestionInput = true;
        this.$nextTick( () => {
          this.$refs.newQuestion.focus();
        })
      },
      addQuestionToConfig() {
        let question = this.lodash.clone(this.newQuestion);
        if(question.text.length) {
          this.standUpData.questions.push(question);
          this.compareConfig();
          this.newQuestion.text = "";
          this.newQuestion.color = "#dddddd";
          this.showNewQuestionInput = false;
        }
      },
      deleteQuestion(index) {
        this.standUpData.questions.splice(index, 1);
        this.compareConfig();
      },
      updateStandUpConfig(configData) {
        console.log(configData);
        return this.$store.dispatch('UPDATE_STANDUP_CONFIG', configData).then( () => {
          console.log(this.$store.getters.getStandUpConfigs);
        })
      }
    },
    mounted() {
      if(this.$store.getters.getStandUpConfigs.length) {
        this.initialStandUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
        this.standUpData = this.lodash.cloneDeep(this.initialStandUpData);
      } else {
        this.$store.dispatch('GET_STANDUP_CONFIGS').then( () => {
          this.initialStandUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
          this.standUpData = this.lodash.cloneDeep(this.initialStandUpData);
        });
      }
    },
  }
</script>

<style scoped>
    .edit-field-icon {
        color: #7e91ff;
    }
    .color-picker-container {
        position: absolute;
        z-index: 99;
        background-color: #fff;
        left: 10em;
        max-width: 30%;
    }
</style>
