<script setup>
import { computed, reactive, ref, watch } from "vue";
import userService from "@/Services/userService";

const props = defineProps({
  selectedUser: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(["saved", "cancel"]);

const form = reactive({
  first_name: "",
  last_name: "",
  company_name: "",
  email: "",
  contact_number: "",
  description: "",
});

const errors = reactive({});
const submitting = ref(false);
const isEditing = computed(() => !!props.selectedUser);

const resetErrors = () => {
  Object.keys(errors).forEach((key) => delete errors[key]);
};

const resetForm = () => {
  form.first_name = "";
  form.last_name = "";
  form.company_name = "";
  form.email = "";
  form.contact_number = "";
  form.description = "";
};

const generateDummyData = () => {
  const firstNames = ["Ada", "Grace", "Alan", "Linus", "Katherine", "Tim"];
  const lastNames = ["Lovelace", "Hopper", "Turing", "Torvalds", "Johnson", "Berners-Lee"];
  const companies = [
    "Analytical Engines Inc.",
    "Codewave Labs",
    "Nimbus Systems",
    "Orbit Analytics",
    "NovaByte",
  ];
  const pick = (list) => list[Math.floor(Math.random() * list.length)];
  const first = pick(firstNames);
  const last = pick(lastNames);
  const company = pick(companies);
  const slug = `${first}.${last}`.toLowerCase();
  const rand = Math.floor(Math.random() * 900 + 100);

  form.first_name = first;
  form.last_name = last;
  form.company_name = company;
  form.email = `${slug}${rand}@example.com`;
  form.contact_number = `+1-555-01${rand}`;
  form.description = "Auto-generated sample user.";
};

const syncForm = (user) => {
  if (!user) {
    resetForm();
    return;
  }
  form.first_name = user.first_name ?? "";
  form.last_name = user.last_name ?? "";
  form.company_name = user.company_name ?? "";
  form.email = user.email ?? "";
  form.contact_number = user.contact_number ?? "";
  form.description = user.description ?? "";
};

watch(
  () => props.selectedUser,
  (user) => {
    resetErrors();
    syncForm(user);
  },
  { immediate: true },
);

const handleSubmit = async () => {
  submitting.value = true;
  resetErrors();

  try {
    let response;
    if (isEditing.value) {
      response = await userService.updateUser(props.selectedUser.id, {
        ...form,
      });
    } else {
      response = await userService.createUser({ ...form });
    }

    const message =
      response?.message ??
      (isEditing.value ? "User updated." : "User created.");
    emit("saved", { message });
    resetForm();
  } catch (error) {
    if (error?.errors) {
      Object.assign(errors, error.errors);
    } else {
      errors.general = ["Something went wrong. Please try again."];
    }
  } finally {
    submitting.value = false;
  }
};

const handleCancel = () => {
  resetErrors();
  resetForm();
  emit("cancel");
};
</script>

<template>
  <form class="flex flex-col gap-4" @submit.prevent="handleSubmit">
    <div v-if="errors.general" class="rounded-lg bg-red-100 p-3 text-sm text-red-700">
      {{ errors.general[0] }}
    </div>
    <div class="flex flex-wrap items-center justify-between gap-2">
      <p class="text-sm text-slate-500">Fields marked with * are required.</p>
      <button
        v-if="!isEditing"
        class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-900 transition hover:-translate-y-0.5"
        type="button"
        @click="generateDummyData"
      >
        <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
          <path
            d="M4 7h16M4 12h10M4 17h7"
            stroke="currentColor"
            stroke-width="2"
            fill="none"
          />
          <path
            d="M17 14v6m-3-3h6"
            stroke="currentColor"
            stroke-width="2"
            fill="none"
          />
        </svg>
        Fill dummy data
      </button>
    </div>

    <div class="flex flex-col gap-2">
      <label for="first_name" class="font-semibold text-slate-800">
        First name <span class="text-red-700">*</span>
      </label>
      <input
        id="first_name"
        v-model="form.first_name"
        type="text"
        placeholder="Ada"
        required
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
      />
      <p v-if="errors.first_name" class="text-sm text-red-700">
        {{ errors.first_name[0] }}
      </p>
    </div>

    <div class="flex flex-col gap-2">
      <label for="last_name" class="font-semibold text-slate-800">
        Last name <span class="text-red-700">*</span>
      </label>
      <input
        id="last_name"
        v-model="form.last_name"
        type="text"
        placeholder="Lovelace"
        required
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
      />
      <p v-if="errors.last_name" class="text-sm text-red-700">
        {{ errors.last_name[0] }}
      </p>
    </div>

    <div class="flex flex-col gap-2">
      <label for="company_name" class="font-semibold text-slate-800">
        Company name <span class="text-red-700">*</span>
      </label>
      <input
        id="company_name"
        v-model="form.company_name"
        type="text"
        placeholder="Analytical Engines Inc."
        required
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
      />
      <p v-if="errors.company_name" class="text-sm text-red-700">
        {{ errors.company_name[0] }}
      </p>
    </div>

    <div class="flex flex-col gap-2">
      <label for="email" class="font-semibold text-slate-800">
        Email <span class="text-red-700">*</span>
      </label>
      <input
        id="email"
        v-model="form.email"
        type="email"
        placeholder="ada@example.com"
        required
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
      />
      <p v-if="errors.email" class="text-sm text-red-700">{{ errors.email[0] }}</p>
    </div>

    <div class="flex flex-col gap-2">
      <label for="contact_number" class="font-semibold text-slate-800">
        Contact number <span class="text-red-700">*</span>
      </label>
      <input
        id="contact_number"
        v-model="form.contact_number"
        type="text"
        placeholder="+1-555-0101"
        required
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
      />
      <p v-if="errors.contact_number" class="text-sm text-red-700">
        {{ errors.contact_number[0] }}
      </p>
    </div>

    <div class="flex flex-col gap-2">
      <label for="description" class="font-semibold text-slate-800">Description</label>
      <textarea
        id="description"
        v-model="form.description"
        rows="3"
        placeholder="Optional notes..."
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
      ></textarea>
      <p v-if="errors.description" class="text-sm text-red-700">
        {{ errors.description[0] }}
      </p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
      <button
        class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
        type="submit"
        :disabled="submitting"
      >
        {{
          submitting ? "Saving..." : isEditing ? "Update user" : "Create user"
        }}
      </button>
      <button
        v-if="isEditing"
        class="rounded-full bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:-translate-y-0.5"
        type="button"
        @click="handleCancel"
      >
        Cancel
      </button>
    </div>
  </form>
</template>
