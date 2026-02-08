<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
  password: "",
});

const submit = () => {
  form.post(route("password.confirm"), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Confirm Password" />

    <div class="flex flex-col gap-6">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-500">
          Secure area
        </p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Confirm password</h1>
        <p class="mt-2 text-sm text-slate-600">
          Please confirm your password before continuing.
        </p>
      </div>

      <form class="flex flex-col gap-4" @submit.prevent="submit">
        <div class="flex flex-col gap-2">
          <label for="password" class="font-semibold text-slate-800">
            Password <span class="text-red-700">*</span>
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            autocomplete="current-password"
            autofocus
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <InputError class="text-sm text-red-700" :message="form.errors.password" />
        </div>

        <div class="flex justify-end">
          <button
            class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
            type="submit"
            :disabled="form.processing"
          >
            Confirm
          </button>
        </div>
      </form>
    </div>
  </GuestLayout>
</template>
