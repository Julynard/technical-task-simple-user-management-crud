<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <div class="flex flex-col gap-6">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-500">
          Create account
        </p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Register</h1>
        <p class="mt-2 text-sm text-slate-600">
          Set up an admin profile to manage users.
        </p>
      </div>

      <form class="flex flex-col gap-4" @submit.prevent="submit">
        <div class="flex flex-col gap-2">
          <label for="name" class="font-semibold text-slate-800">
            Name <span class="text-red-700">*</span>
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            placeholder="Admin User"
            required
            autofocus
            autocomplete="name"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <InputError class="text-sm text-red-700" :message="form.errors.name" />
        </div>

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
            autocomplete="username"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <InputError class="text-sm text-red-700" :message="form.errors.email" />
        </div>

        <div class="flex flex-col gap-2">
          <label for="password" class="font-semibold text-slate-800">
            Password <span class="text-red-700">*</span>
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            placeholder="Create a password"
            required
            autocomplete="new-password"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <InputError class="text-sm text-red-700" :message="form.errors.password" />
        </div>

        <div class="flex flex-col gap-2">
          <label for="password_confirmation" class="font-semibold text-slate-800">
            Confirm password <span class="text-red-700">*</span>
          </label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            placeholder="Repeat password"
            required
            autocomplete="new-password"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <InputError class="text-sm text-red-700" :message="form.errors.password_confirmation" />
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
          <Link
            :href="route('login')"
            class="text-sm font-semibold text-slate-600 underline decoration-slate-300 underline-offset-4 transition hover:text-slate-900"
          >
            Already registered?
          </Link>

          <button
            class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
            type="submit"
            :disabled="form.processing"
          >
            Register
          </button>
        </div>
      </form>
    </div>
  </GuestLayout>
</template>
