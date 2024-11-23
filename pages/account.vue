<script setup>


import Error from "~/error.vue";

const router = useRouter()

const name = ref("");
const email = ref("");
const password = ref("");

const errors = ref(false);
const message = ref('');

const goBack = () => {
  if (window.history.length > 1) {
    router.back();
  } else {
    router.push('/');
  }
};

const { $api } = useNuxtApp();

const user = ref(null);

const getUser = async () => {
  try {
    const response = await $api.post('/api/graphql', {
      query:
          `
        {
        meUser {
            email,
            name,
            _id,
            id
          }
        }
        `
    });
    user.value = response.data.data.meUser;
    name.value = user.value.name;
    email.value = user.value.email;
  }catch (e) {

  }
}

const changeInfo = async () => {
  errors.value = false;
  message.value = "";
  if (name.value === "" || email.value === "" || password.value === "") {
    errors.value = true;
    message.value = "Veuillez remplir tous les champs !";
    triggerModal()
    return;
  }

  try {
    const response = await $api.post('/api/graphql', {
      query: `
        mutation {
          updateUser(input: {
            id: "${user.value.id}",
            email: "${email.value}",
            password: "${password.value}",
            name: "${name.value}"
          }) {
            user {
              id
            }
          }
        }
      `
    });

    message.value = "Informations mises à jour avec succès !";

    user.value.name = name.value;
  } catch (e) {
    console.error("Erreur lors de la mutation :", e);
    errors.value = true;
    message.value = "Une erreur est survenue. Veuillez réessayer plus tard.";
  }

  triggerModal()
}


const modalRefs = ref()

const triggerModal = () => {
  modalRefs.value.toggleMenu();
}

onMounted(() => {
  getUser()
} )
</script>

<template>
  <div class="px-[5%]">
    <div class="flex items-center gap-6 pb-12 border-b border-white/20">
      <button @click="goBack" class="text-white text-sm flex items-center gap-1 hover:text-white/20 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
      </button>
      <h2 class="font-bold text-2xl">Mon compte</h2>
    </div>
    <div>
      <div class="flex gap-8 -mt-4 border-white/20 mb-24">
        <div>
          <img src="#" alt="">
          <div class="bg-blue-500 w-28 h-28 rounded-full">
            <p class="text-5xl flex justify-center items-center h-full" v-if="user">{{ user.name[0] }}</p>
          </div>
        </div>
        <div class="flex flex-col justify-end" v-if="user">
          <h3 class="text-lg font-medium">{{ user.name }}</h3>
          <p class="text-white/50">badyss.blt@gmail.com</p>
          <form @submit.prevent="">
            <input type="file" name="picture" id="picture" class="hidden" />

            <label for="picture" class="transition-all flex gap-2 px-4 py-2 bg-primary text-white rounded border border-transparent cursor-pointer hover:border hover:border-primary hover:text-primary hover:bg-transparent">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
              </svg>

              Choisir un fichier
            </label>

          </form>
        </div>


      </div>
      <div class="">
        <p class="font-medium text-lg pb-6 border-b border-white/20">Informations personnelles</p>
        <form class="mt-4 flex flex-col gap-4" @submit.prevent="">
          <Input label="Nom" placeholder="Entrer un nom" v-model="name"/>
          <Input label="Email" placeholder="Entrer un email" v-model="email"/>
          <Input label="Mot de passe" placeholder="Entrer un mot de passe" v-model="password"/>
          <Button @click="triggerModal">Modifier mes informations</Button>
          <Warn v-if="errors">
            {{ message }}
          </Warn>
        </form>
      </div>
      <ConfirmationModal @onConfirm="changeInfo" ref="modalRefs" title="Voulez-vous changer vos informations ?" description="Vos informations seront changées et vous serez déconnecté" btnText="Changer mes informations">

      </ConfirmationModal>
    </div>
  </div>
</template>

