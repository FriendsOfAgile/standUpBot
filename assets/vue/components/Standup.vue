<template>
    <div class="w-full flex flex-col">
        <div class="flex justify-between px-6 pt-6">
            <h3 v-if="!showEditNameInput" class="font-bold text-2xl text-gray-700 cursor-pointer" @click="showEditNameInput = true, focusInput('standupName')"><span class="font-normal">Standup</span> {{ getStandUpName }}</h3>
            <input v-if="showEditNameInput" ref="standupName" class="focus:outline-none w-3/4 text-gray-700 px-3 text-2xl font-bold" type="text" v-model="standUpData.name" @input="compareConfig" @keyup.enter="showEditNameInput = false" @blur="showEditNameInput = false">
            <div>
                <button v-if="edited" @click="saveStandUpConfig(standUpData)" class="bg-accentColor text-white border-2 border-accentColor font-bold py-2 px-4 rounded focus:outline-none">
                <span>
                    <font-awesome-icon icon="save" class="mr-1"/>
                    Save changes
                </span>
                </button>
                <div class="bg-white text-white border-2 border-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed opacity-50 text-gray-500" v-else>
                <span>
                    <font-awesome-icon icon="check" class="mr-1"/>
                    Config saved
                </span>
                </div>
            </div>

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
                            <input class="ml-1 py-1 px-2 focus:outline-none" placeholder="Enter your question" ref="question" v-model="newQuestion.text" type="text" @input="compareConfig" @blur="addQuestionToConfig"/>
                            <div style="width: 24px; height: 24px;" v-if="newQuestion.text" :style="{'background-color': newQuestion.color}" class="rounded cursor-pointer" @click="showColorPicker = 'newQuestion'"></div>
                            <div class="color-picker-container p-2 z-max" v-if="showColorPicker === 'newQuestion'" @mouseleave="showColorPicker = false">
                                <v-swatches v-model="newQuestion.color" @input="showColorPicker = false" popover-y="up" inline="true"/>
                            </div>
                            <transition name="component-fade">
                                <span class="text-accentColor text-xs ml-4 mt-1 cursor-pointer" v-if="newQuestion.text.length" @click="addQuestionToConfig">save</span>
                            </transition>
                        </div>
                    </transition>
                    <div class="flex full mt-6 text-gray-700 items-center cursor-pointer" @click="showNewQuestionInput = true, focusInput('question')">
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
        showEditNameInput: false,
        showColorPicker: false,
        showNewQuestionInput: false,
        newQuestion: {
          text: "",
          color: '#dddddd'
        },
        edited: false,
        dataLoaded: false,
        workspaceMembers: []
      }
    },
    methods: {
      compareConfig() {
        if(!this.lodash.isEqual(this.standUpData, this.initialStandUpData)) {
          this.edited = true;
        } else this.edited = false;
      },
      focusInput(ref) {
        this.$nextTick( () => {
          this.$refs[ref].focus();
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
      saveStandUpConfig(configData) {
        this.$loading(true);
        if(this.standUpData.id) {
          return this.$store.dispatch('UPDATE_STANDUP_CONFIG', configData).then( () => {
            this.edited = false;
            this.initialStandUpData = this.lodash.cloneDeep(configData); // this is needed to update initialStandup, so comparing works if component is not re-rendered
            this.$loading(false);
          })
        } else {
          return this.$store.dispatch('SAVE_NEW_STANDUP_CONFIG', configData).then( () => {
            // TODO getter vuex to allow keep editing same config (need to get id from store and change route path / push to saved config
            this.$loading(false);
            this.$router.push('/dashboard/standups/');
          })
        }

      }
    },
    computed: {
      getStandUpName() {
        if(this.dataLoaded) {
          if(this.standUpData.name) {
            return this.standUpData.name;
          } else {
            this.standUpData.name = this.initialStandUpData.name;
            this.compareConfig();
            return this.standUpData.name;
          }
        }
      }
    },
    mounted() {
      if(this.$route.params.id !== 'new') {
        // get workspace members
        if(this.$store.getters.getWorkspaceMembers.length) {
          this.workspaceMembers = this.$store.getters.getWorkspaceMembers;
          console.log('1 ', this.workspaceMembers);
        } else {
          this.$loading(true);
          this.$store.dispatch('GET_WORKSPACE_MEMBERS', this.$route.params.id)
            .then( () => {
              this.workspaceMembers = this.$store.getters.getWorkspaceMembers;
              this.$loading(false);
              console.log('2 ', this.workspaceMembers);
            });
        }
        if(this.$store.getters.getStandUpConfigs.length) {
          this.initialStandUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
          this.standUpData = this.lodash.cloneDeep(this.initialStandUpData);
          this.dataLoaded = true;
        } else {
          this.$loading(true);
          this.$store.dispatch('GET_STANDUP_CONFIGS').then( () => {
            this.initialStandUpData = this.$store.getters.getStandUpConfigData(Number(this.$route.params.id));
            this.standUpData = this.lodash.cloneDeep(this.initialStandUpData);
            this.dataLoaded = true;
            this.$loading(false);
          });
        }
      }
      else {
        this.initialStandUpData = {
          name: "New standup",
          messageBefore: "",
          messageAfter: "",
          questions: [],
          members: [],
        };
        this.standUpData = this.lodash.cloneDeep(this.initialStandUpData);
        this.dataLoaded = true;
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
