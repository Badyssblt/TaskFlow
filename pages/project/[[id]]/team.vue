<script setup>


const router = useRouter();
const route = useRoute();

const id = ref(0);

const name = ref('');
const startedAt = ref();
const endAt = ref();

const searchName = ref('');

const {$api} = useNuxtApp();

const project = ref({});
const teams = ref([]);

const invitationsSent = ref({});


const modalRef = ref(null);
const friendModal = ref(null);

const searchedUsers = ref([]);
const openModal = () => {
  if (modalRef.value) {
    modalRef.value.toggleMenu();
  }
};


const goBack = () => {
  if (window.history.length > 1) {
    router.back();
  } else {
    router.push('/');
  }
};

const getTeams = async () => {
  try {
    const response = await $api.post("/api/graphql", {
      query: `
          {
            project(id: "/api/projects/${id.value}"){
              id
                  name
                  description
                  priority
                  visibility
                  state
                  teamItems {
                    edges {
                      node {
                        id
                        user {
                          id,
                          name
                        }
                        createdAt
                      }
                    }
                  }
            }
          }
        `
    });
    teams.value = response.data.data.project.teamItems.edges;
  } catch (e) {
    console.log(e)
  }
}

const deleteTeam = async (teamId) => {
  try {
    await $api.post("/api/graphql", {
      query: `
        mutation {
          deleteTeamItem(input: {id: "${teamId}"}) {
            clientMutationId
          }
        }
      `
    });

    teams.value = teams.value.filter((team) => team.node.id !== teamId);
  } catch (e) {
    console.log(e);
  }
};

const openFriendMenu = () => {
  friendModal.value.toggleMenu();

}

const searchUser = async () => {
  try {
    const response = await $api.get('/api/users', {
      params: {
        name: searchName.value
      }
    });
    searchedUsers.value = response.data['hydra:member'];
  }catch (e) {

  }
}

const sendInvite = async (userId) => {
  try {
    const response = await $api.post('/api/graphql', {
      query: `
        mutation {
          createFriend(input: { receiver: "${userId}", state: "waiting" }){
            friend {
              id
            }
          }
        }
      `
    });
    invitationsSent.value[userId] = true;
  }catch (e) {

  }
}




onMounted(async () => {
  id.value = route.params.id;
  await getTeams();
})
</script>

<template>
  <div class="flex min-h-screen">
    <Aside/>
    <div class="w-full bg-[#12131C] px-24 pt-6">
      <button @click="goBack" class="text-white/60 text-sm flex items-center gap-1 hover:text-white transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
        Retour aux projets
      </button>
      <div class="mt-12 border border-white/20 rounded px-8 py-8 min-h-screen">

        <div class="border-b border-white/20 pb-12">
          <h2 class="text-2xl font-semibold">Equipe</h2>
          <p class="text-white/60 text-sm my-4">Ajouter, modifier ou supprimer des membres de votre équipe</p>
          <button  class="flex items-center gap-4 border border-primary px-4 py-2 rounded bg-background" @click="openFriendMenu"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
            Ajouter des amis
          </button>
          <div>
            <div>

            </div>

          </div>
        </div>
        <div class="flex flex-wrap mt-12 gap-4" v-if="teams.length !== 0">
          <TeamCard
              v-for="team in teams"
              :team="team.node"
              :key="team.node.id"
              @deleteTeam="deleteTeam"
          />
        </div>
        <div v-else>
          <p class="text-white/60 mt-4">Vous n'avez aucun membre dans votre équipe...</p>
        </div>
      </div>
    </div>

  </div>

  <!--  MODALS -->

  <Modal title="Ajouter un amis" ref="friendModal">
      <div>
        <form @submit.prevent="searchUser" class="mt-4">
          <Input :label="'Rechercher'" placeholder="John Doe" v-model="searchName"/>

        </form>
        <div class="flex flex-col gap-4 mt-2">
          <NotificationCard v-for="item in searchedUsers" class="flex justify-between items-center">
            <p>{{ item.name }}</p>
            <Button @click="sendInvite(item['@id'])">
              {{ invitationsSent[item['@id']] ? 'Invitation envoyée' : 'Ajouter' }}
            </Button>
          </NotificationCard>
        </div>
      </div>
  </Modal>

  <Modal title="Créer une tâche" ref="modalRef">
    <form @submit.prevent="createTask" class="flex flex-col gap-6 mt-4 md:w-[600px] w-full">
      <Input label="Nom de la tâche" placeholder="Entrer un nom de tâche" v-model="name"/>
      <div class="flex justify-between gap-6">
        <Input label="Début de la tâche" placeholder="26-09-24" type="date" class="w-full" v-model="startedAt"/>
        <Input label="Fin de la tâche" placeholder="26-09-24" type="date" class="w-full" v-model="endAt"/>
      </div>
      <Button>Créer la tâche</Button>
      <!--              <Button>Modifier la tâche</Button>-->
      <!--              <Button class="bg-lime-700/60">Définir comme finit</Button>-->
      <!--              <Button type="button" class="bg-red-900" @click="deleteTaskConfirmation">Supprimer la tâche</Button>-->
    </form>
  </Modal>
  <!--  ENDMODALS -->

</template>

<style scoped>

</style>