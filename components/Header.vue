<script setup>
import { useAuth } from "@/store/auth.js";
import NotificationCard from "~/components/NotificationCard.vue";

const isShow = ref(true);

const store = useAuth();
const notifications = ref([]);

const accountDropDownState = ref(false);

const notificationModal = ref(null);

const { $api } = useNuxtApp();

const setStateFriends = async (id, state) => {
  try {
    const response = await $api.patch(`/api/friends/${id}`, {
      state: state
    },
        {
          headers: {
            'Content-Type': "application/merge-patch+json"
          }
        })
    await getNotifications()
    notificationModal.value.toggleMenu();
  }catch (e) {

  }
}

const setStateTeam = async (id, state) => {
  try {
    const response = await $api.post(`/api/graphql`, {
          query: `
          mutation {
            updateTeamItem(input: { id: "${id}", state: "${state}" }){
              teamItem {
                id
              }
            }
          }
          `
        })
    await getNotifications()
    notificationModal.value.toggleMenu();
  }catch (e) {

  }
}

const handleResize = () => {
  isShow.value = window.innerWidth >= 768;
};

const getNotifications = async () => {
    try {
      const [friendsResponse, itemsResponse] = await Promise.all([
        $api.get('/api/friends', { params: { state: 'waiting' } }),
        $api.get('/api/team_items')
      ]);

      const friends = friendsResponse.data['hydra:member'].map(friend => ({
        ...friend,
        category: 'friend'
      }));

      const items = itemsResponse.data['hydra:member'].map(item => ({
        ...item,
        category: 'teamItem'
      }));

      notifications.value = [...friends, ...items];
    }catch (e) {

    }
}

const accountDropDown = () => {
  accountDropDownState.value = !accountDropDownState.value
}

const isAuthenticated = ref(store.isAuthenticated);

const logout = () => {
  store.logout();
  navigateTo("/login")
}


watch(
    () => store.isAuthenticated,
    (newVal) => {
      isAuthenticated.value = newVal;
    }
);


onMounted(() => {
  handleResize();
  window.addEventListener('resize', handleResize);
  getNotifications();
});
</script>

<template>
  <header class="px-[5%] py-4 md:py-8">
    <div class="flex justify-between md:hidden ">
      <NuxtLink to="/"><h1 class="font-bold text-xl hover:text-primary transition-all">TaskFlow</h1></NuxtLink>
      <button @click="toggleMenu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
        </svg>
      </button>
      <button>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>

      </button>
    </div>
    <menu class="fixed w-full h-screen  bg-background z-50 top-0 left-0 md:static md:bg-transparent md:h-fit" v-show="isShow">
      <button class="absolute right-4 top-4 md:hidden" @click="toggleMenu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="flex justify-center items-center gap-24 flex-col mt-8 md:flex-row md:justify-between md:mt-0">
        <NuxtLink to="/"><h1 class="font-bold text-xl hover:text-primary transition-all">TaskFlow</h1></NuxtLink>
        <div class="flex gap-4">
          <NuxtLink to="/login" v-if="!isAuthenticated" class="hover:text-primary transition-all">Se connecter</NuxtLink>
        </div>
        <div v-if="isAuthenticated" class="flex gap-6">
          <button @click="() => {
            notificationModal.toggleMenu();
            accountDropDown()
          }">
            <!--        ICON NOTIFICATION-->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" v-if="notifications.length === 0">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" v-else>
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
            </svg>

          </button>
          <div class="relative">
            <button class="flex items-center justify-center" @click="accountDropDown">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>
            </button>

            <div :class="['absolute right-0 mt-2 w-48 bg-background rounded-lg shadow-lg border border-white/20 transition-all duration-300 ease-in-out transform origin-top', accountDropDownState ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-0']" v-if="accountDropDownState || accountDropDownState === false">
              <ul class="py-2">
                <li>
                  <NuxtLink to="/account" class="block px-4 py-2 text-white hover:bg-primary/40 transition-all">Mon compte</NuxtLink>
                </li>
                <li>
                  <button @click="logout" class="block px-4 py-2 text-white hover:bg-primary/40 transition-all w-full text-start">Se déconnecter</button>
                </li>
              </ul>
            </div>
          </div>



          <Modal ref="notificationModal">
            <h2 class="font-medium">Mes notifications</h2>
            <div v-if="notifications.length !== 0" class="flex flex-col gap-4 mt-4">
              <NotificationCard
                  v-for="notification in notifications"
                  :key="notification.id"
                  class="flex items-center gap-6"
              >


                <!-- Affichage spécifique pour les amis -->
                <template v-if="notification.category === 'friend'">
                  <h3>
                    {{ notification.applicant?.name || notification.team?.name }}
                  </h3>
                  <p class="text-xs text-white/60">Vous a demandé en ami</p>
                  <div class="flex gap-4">
                    <button class="bg-lime-800 p-1 rounded" @click="setStateFriends(notification.id, 'accepted')">Accepter</button>
                    <button class="bg-red-800 p-1 rounded" @click="setStateFriends(notification.id, 'denied')">Refuser</button>
                  </div>
                </template>

                <!-- Affichage spécifique pour les items de l'équipe -->
                <template v-else-if="notification.category === 'teamItem'">
                  <p class="text-xs text-white/60">{{ notification.project.owner.name }} vous a invité à un projet</p>
                  <div class="flex gap-4">
                    <button class="bg-lime-800 p-1 rounded" @click="setStateTeam(notification['@id'], 'accepted')">Accepter</button>
                    <button class="bg-red-800 p-1 rounded" @click="setStateTeam(notification['@id'], 'denied')">Refuser</button>
                  </div>
                </template>
              </NotificationCard>
            </div>
            <p v-else class="text-white/60 text-center mt-4">Aucune notifications</p>
          </Modal>
        </div>
      </div>

    </menu>
  </header>
</template>

<style scoped>

</style>