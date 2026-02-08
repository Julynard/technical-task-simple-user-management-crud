<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div class="flex flex-col gap-6">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-500">
          Welcome back
        </p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Log in</h1>
        <p class="mt-2 text-sm text-slate-600">
          Access the admin dashboard and manage users.
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

        <div class="flex flex-col gap-2">
          <label for="password" class="font-semibold text-slate-800">
            Password <span class="text-red-700">*</span>
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="current-password"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <InputError class="text-sm text-red-700" :message="form.errors.password" />
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
          <Checkbox name="remember" v-model:checked="form.remember" />
          Remember me
        </label>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-sm font-semibold text-slate-600 underline decoration-slate-300 underline-offset-4 transition hover:text-slate-900"
          >
            Forgot your password?
          </Link>

          <button
            class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
            type="submit"
            :disabled="form.processing"
          >
            Log in
          </button>
        </div>

        <p class="text-sm text-slate-500">
          New here?
          <Link
            :href="route('register')"
            class="font-semibold text-slate-900 underline decoration-amber-200 underline-offset-4"
          >
            Create an account
          </Link>
        </p>
      </form>
    </div>
  </GuestLayout>
</template>
