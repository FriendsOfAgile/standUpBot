<template>
    <div class="w-full flex flex-col pb-12 ">
        <div class="flex justify-between px-6 pt-6">
            <h3 v-if="!showEditNameInput" class="font-bold text-2xl text-gray-700 cursor-pointer" @click="showEditNameInput = true, focusInput('standupName')"><span class="font-normal">Standup</span> {{ getStandUpName }}</h3>
            <input v-if="showEditNameInput" ref="standupName" class="focus:outline-none w-3/4 text-gray-700 px-3 text-2xl font-bold" type="text" v-model="standUpData.name" @input="compareConfig" @keyup.enter="showEditNameInput = false" @blur="showEditNameInput = false">
            <div>
                <button v-if="edited" @click="validateConfig(standUpData)" class="bg-accentColor text-white border-2 border-accentColor font-bold py-2 px-4 rounded focus:outline-none">
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


            <div class="w-full flex space-x-6 mt-4">
                <h3 class="text-lg font-bold text-gray-700 border-b-4 mt-4 mb-2 border-gray-400 transition duration-300 ease-in-out w-content cursor-pointer" :class="{'border-accentColor': activeTab === 'questions'}" @click="activeTab = 'questions'">Questions</h3>
                <h3 class="text-lg font-bold text-gray-700 border-b-4 mt-4 mb-2 border-gray-400 transition duration-300 ease-in-out w-content cursor-pointer" :class="{'border-accentColor': activeTab === 'members'}" @click="activeTab = 'members'">Members</h3>
            </div>

                <div class="flex flex-col p-1 mt-2" v-if="activeTab === 'questions'">
                    <div class="flex flex-col ">
                        <div class="w-full flex-flex-col space-y-1" v-for="(question, index) in standUpData.questions" :key="question['@id']">
                            <div class="mt-4 w-full flex items-center relative">
                                <font-awesome-icon icon="trash-alt" class="text-red-400 mr-1 cursor-pointer text-xl" @click="deleteQuestion(index)"/>
                                <font-awesome-icon icon="eye-dropper" class="ml-4 text-xl mr-4 text-gray-500 cursor-pointer" @click="showColorPicker = index"/>
                                <input class="ml-2 border-l-8 px-3 focus:outline-none w-full cursor-pointer" type="text" :style="{'border-color': question.color}" v-model="question.text" @input="compareConfig" />
                                <transition name="fade">
                                    <div class="color-picker-container p-2 z-max" v-if="showColorPicker === index" @mouseleave="showColorPicker = false">
                                        <v-swatches v-model="question.color" @input="compareConfig(), showColorPicker = false" popover-y="up" inline="true"/>
                                    </div>
                                </transition>
                            </div>
                        </div>
                        <transition name="component-fade" mode="out-in">
                            <div class="mt-4 w-full flex items-center relative" :style="{'border-color': newQuestion.color}" v-if="showNewQuestionInput" @keyup.enter="addQuestionToConfig">
                                <font-awesome-icon icon="check" v-if="newQuestion.text.length" class="text-xl mr-1 text-green-500 cursor-pointer" @click="addQuestionToConfig"/>
                                <font-awesome-icon icon="eye-dropper" v-if="newQuestion.text" class="ml-4 text-xl mr-4 text-gray-500 cursor-pointer" @click="showColorPicker = 'newQuestion'"/>
                                <input class="w-full border-l-8 px-3 focus:outline-none question-input"  :class="{'ml-1': newQuestion.text && newQuestion.text.length}" placeholder="Enter your question" ref="question" v-model="newQuestion.text" type="text" @input="compareConfig" @blur="addQuestionToConfig"/>
                                <div class="color-picker-container p-2 z-max" v-if="showColorPicker === 'newQuestion'" @mouseleave="showColorPicker = false">
                                    <v-swatches v-model="newQuestion.color" @input="showColorPicker = false" popover-y="up" inline="true"/>
                                </div>
                            </div>
                        </transition>
                        <div class="flex full mt-6 text-gray-700 items-center cursor-pointer" @click="showNewQuestionInput = true, focusInput('question')">
                            <font-awesome-icon icon="plus"/>
                            <span class="ml-2">Add a question</span>
                        </div>
                    </div>
                </div>



                <div class="flex flex-col p-1 mt-2" v-if="activeTab === 'members'">
                    <div class="w-full" v-if="standUpData.members.length">
                        {{ standUpData.members }}
                        <!-- Members -->
                    </div>
                    <div class="w-full">
                        <h3 class="text-xl font-bold pb-6 text-gray-700">There are no members yet :( Type in username in field below and add some members to the standup team!</h3>
                        <label for="searchMembers" class="text-gray-500">Search members:
                            <input id="searchMembers" class="focus:outline-none w-full text-gray-700 border border-gray-500 rounded mt-2 p-3" autofocus type="text" placeholder="Enter username" v-model="searchMembersInput">
                        </label>
                        <button class="bg-gray-500 mt-4 focus:outline-none hover:bg-accentColor text-white text-sm font-bold py-2 px-4 w-content" @click="membersFiltered = workspaceMembers" v-if="!membersFiltered.length">show all workspace members</button>
                        <button class="bg-gray-500 mt-4 focus:outline-none hover:bg-accentColor text-white text-sm font-bold py-2 px-4 w-content" @click="membersFiltered = []" v-else >hide all workspace members</button>
                        <transition name="component-fade" mode="out-in">
                            <div class="w-full grid grid-cols-6 gap-4 mt-4" v-if="membersFiltered.length">
                                <div class="p-4 flex flex-col justify-center items-center cursor-pointer transition duration-300 ease-in-out hover:shadow-lg" v-for="member in membersFiltered" :key="member.name">
                                    <div @click="addMemberToConfig(member)">
                                        <img class="w-auto m-auto rounded-full" :src="member.avatar"/>
                                        <div class="w-full text-center mt-4 text-lg">
                                            {{ member.name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>


        </div>

        <modal name="errors-modal" width="50%" height="auto" class="shadow-lg">
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">There are mistakes in config.</strong>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg @click="closeErrorsModal" class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
                <ul class="mt-3 space-y-2">
                    <li v-for="(error, index) in errors" :key="index">
                        <div>{{ error }}</div>
                    </li>
                </ul>
            </div>

        </modal>

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
        activeTab: 'questions',
        showEditNameInput: false,
        showColorPicker: false,
        showNewQuestionInput: false,
        newQuestion: {
          text: "",
          color: '#dddddd'
        },
        edited: false,
        dataLoaded: false,
        workspaceMembers: [],
        searchMembersInput: "",
        membersFiltered: [],
        errors: [],
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
      addMemberToConfig(data) {
        console.log('addMember: ', data);
        const member = {
          config: this.standUpData["@id"],
          uid: data.uid,
          "canRead": true,
          "canWrite": true,
          "canEdit": false,
        };
       // this.$loading(true);
       return this.$store.dispatch('ADD_MEMBER_TO_CONFIG', member)
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
      },
      validateConfig(configData) {
        console.log(configData);
        this.errors = [];
        if(configData.questions.length && configData.messageBefore && configData.messageAfter) {
          for(let question of configData.questions) {
            if(!question.text.length) {
              this.errors.push("Question can't be empty. Please enter some text before saving.");
            }
          }
          if(this.errors.length) {
            this.$modal.show('errors-modal');
          } else {
            this.$modal.hide('errors-modal');
            this.saveStandUpConfig(configData)
          }
        } else {
          if(!configData.messageBefore) {
            this.errors.push('Please add Intro Message before saving.');
          }
          if(!configData.messageAfter) {
            this.errors.push('Please add Outro Message before saving.');
          }
          if(!configData.questions.length) {
            this.errors.push('Please, add some questions before saving.');
          }
          this.$modal.show('errors-modal');
        }
      },
      closeErrorsModal() {
        this.errors = [];
        this.$modal.hide('errors-modal');
      },
      getMemberFromWorkSpace(data, type) {
        if(type === 'byName') {
          let username = data;
          this.membersFiltered = this.workspaceMembers.filter( (item) => item.name.toLowerCase().includes(username.toLowerCase()));
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
        } else {
          this.$loading(true);
          this.$store.dispatch('GET_WORKSPACE_MEMBERS')
            .then( () => {
              this.workspaceMembers = this.$store.getters.getWorkspaceMembers;
              this.$loading(false);
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

    watch: {
      searchMembersInput(username) {
        this.getMemberFromWorkSpace(username, 'byName');
      }
    }
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
