<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm } from "@inertiajs/vue3";

defineProps({
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
});

const submit = () => {
  form.post(route("password.email"));
};
</script>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />

    <div class="flex flex-col gap-6">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-500">
          Password help
        </p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Forgot password</h1>
        <p class="mt-2 text-sm text-slate-600">
          Enter your email and we will send a reset link.
        </p>
      </div>

      <div v-if="status" class="rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
        {{ status }}
      </div>

      <form class="flex flex-col gap-4" @submit.prevent="submit">
        <div class="flex flex-col gap-2">
          <label for="email" class="font-semibold text-slate-800">
            Email <span class="text-red-700">*</span>
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="admin@example.com"
            required
            autofocus
            autocomplete="username"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <InputError class="text-sm text-red-700" :message="form.errors.email" />
        </div>

        <div class="flex justify-end">
          <button
            class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
            type="submit"
            :disabled="form.processing"
          >
            Email reset link
          </button>
        </div>
      </form>

    </div>
  </GuestLayout>
</template>
