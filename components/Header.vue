<script setup>
import { useAuth } from "@/store/auth.js";
import NotificationCard from "~/components/NotificationCard.vue";

const isShow = ref(true);

const store = useAuth();
const notifications = ref([]);

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

const handleResize = () => {
  isShow.value = window.innerWidth >= 768;
};

const getNotifications = async () => {
    try {
      const response = await $api.get('/api/friends', {
        params: {
          state: 'waiting'
        }
      });
      notifications.value = response.data['hydra:member'];
    }catch (e) {

    }
}

const toggleMenu = () => {
  isShow.value = !isShow.value;
}

const isAuthenticated = ref(store.isAuthenticated);


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
      <h1 class="font-bold text-xl">TaskFlow</h1>
      <button @click="toggleMenu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
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
        <h1 class="font-bold text-xl">TaskFlow</h1>
        <div class="flex gap-4">
          <button>Fonctionnalités</button>
          <NuxtLink to="/login" v-if="!isAuthenticated">Se connecter</NuxtLink>
          <button>Commencer</button>
        </div>
        <div>
          <button @click="notificationModal.toggleMenu">
            <!--        ICON NOTIFICATION-->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" v-if="notifications.length === 0">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" v-else>
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
            </svg>

          </button>
          <Modal ref="notificationModal">
            <h2 class="font-medium">Mes notifications</h2>
            <div v-if="notifications.length !== 0" class="flex flex-col gap-4 mt-4">
              <NotificationCard
                  v-for="notification in notifications"
                  :key="notification.id"
                  class="flex items-center gap-6"
              >
              <h3>{{ notification.applicant.email}}
              <p class="text-xs text-white/60">Vous a demandez en amis</p>
              </h3>
                <div class="flex gap-4">
                  <button class="bg-lime-800 p-1 rounded" @click="setStateFriends(notification.id, 'accepted')">Accepter</button>
                  <button class="bg-red-800 p-1 rounded" @click="setStateFriends(notification.id, 'denied')">Refuser</button>
                </div>
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