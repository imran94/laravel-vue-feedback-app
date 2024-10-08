<script setup>
import { ref } from 'vue'
import { customFetch } from '@/utils'
import { useAuthStore } from '@/stores/auth'
import router from '@/router'
import PasswordField from '@/components/PasswordField.vue'
import AvatarInputField from '@/components/AvatarInputField.vue'

const auth = useAuthStore()
const isLoading = ref(false)
const name = ref('')
const email = ref('')
const password = ref('')
const isAdmin = ref(false)
const isInputError = ref(false)
const errorMessage = ref('')
const avatar = ref(null)
const avatarUrl = ref(null)

async function register() {
  isLoading.value = true

  const formData = new FormData()
  formData.append('name', name.value)
  formData.append('email', email.value)
  formData.append('password', password.value)
  if (avatar.value) {
    formData.append('avatar', avatar.value)
  }

  try {
    const { response, data } = await customFetch('/register', 'POST', formData, false)
    if (response.ok) {
      auth.name = data.name
      router.push({ name: 'registrationSuccess' })
    } else {
      errorMessage.value = data
      isInputError.value = true
    }
  } catch (error) {
    console.error(error)
    errorMessage.value = { message: error.message }
  }
  isLoading.value = false
}

function setAvatar(event) {
  const file = event.target.files[0]
  avatar.value = file
  avatarUrl.value = URL.createObjectURL(file)
}

function removeSelectedAvatar() {
  avatar.value = null
  avatarUrl.value = null
}
</script>

<template>
  <div class="section">
    <div class="body center-absolute">
      <h4 class="title">Sign Up</h4>

      <form @submit.prevent="register">
        <div class="m-form-group">
          <label for="nameInput" class="form-label">Name</label>
          <input
            type="text"
            id="nameInput"
            v-model="name"
            class="form-control"
            maxlength="255"
            required
          />
          <div v-if="errorMessage?.errors?.name">
            <span v-for="errorText in errorMessage.errors.name" :key="errorText" class="error">
              {{ errorText }}
            </span>
          </div>
        </div>

        <div class="m-form-group">
          <label for="emailInput" class="form-label">Email address</label>
          <input
            type="email"
            id="emailInput"
            v-model="email"
            class="form-control"
            placeholder="name@example.com"
            maxlength="255"
            required
          />
          <div v-if="errorMessage?.errors?.email">
            <span v-for="errorText in errorMessage.errors.email" :key="errorText" class="error">
              {{ errorText }}
            </span>
          </div>
        </div>

        <div class="m-form-group">
          <password-field v-model="password" />
          <div v-if="errorMessage?.errors?.password">
            <span v-for="errorText in errorMessage.errors.password" :key="errorText" class="error">
              {{ errorText }}
            </span>
          </div>
        </div>

        <avatar-input-field
          :currentAvatarUrl="null"
          :newAvatarUrl="avatarUrl"
          @on-avatar-selected="setAvatar"
          @on-avatar-removed="removeSelectedAvatar"
        />

        <!-- <div class="m-form-group form-check">
          <input class="form-check-input" type="checkbox" v-model="" id="isAdminCheckbox" />
          <label class="form-check-label" for="isAdminCheckbox"> Admin </label>
        </div> -->

        <div class="m-form-group">
          <span style="font-size: 0.8em"
            >Already have an account?
            <router-link :to="{ name: 'login' }">Sign in</router-link></span
          >
        </div>
        <div v-show="errorMessage?.message" class="general-error">{{ errorMessage.message }}</div>
        <button type="submit" class="btn btn-primary btn-full" :disabled="isLoading">
          <span v-show="!isLoading">Register</span>
          <div v-show="isLoading" class="spinner-grow" role="status"></div>
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.section {
  display: flex;
  align-items: center;
  justify-content: center;

  margin-top: 3em;
}

.title {
  width: 100%;
  text-align: center;
  margin-bottom: 1.5em;
}

.body {
  width: 500px;
  border: 1px solid silver;
  border-radius: 3%;
  padding: 1em;
}

form {
  display: flex;
  flex-direction: column;
  align-content: flex-start;
  justify-content: center;
  row-gap: 1em;
}

.m-form-group {
  width: 100%;
}

.error,
.general-error {
  color: red;
  font-size: 0.8em;
}

.general-error {
  padding-top: 0.2em;
  padding-left: 0.5em;
  padding-bottom: 0.5em;
}
a {
  text-decoration: none;
  color: light-dark(var(--light-link-text), var(--dark-link-text));
}

a:hover {
  text-decoration: underline;
}

.avatar-select {
  align-self: center;
}
</style>
