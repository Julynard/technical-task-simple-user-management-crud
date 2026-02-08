<script setup>
import { onMounted, ref } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BaseModal from "@/Components/Crud/BaseModal.vue";
import UserForm from "@/Components/Crud/UserForm.vue";
import UserList from "@/Components/Crud/UserList.vue";
import { Head } from '@inertiajs/vue3';
import useUsers from "@/Composables/useUsers";

const {
  users,
  meta,
  loading,
  loadError,
  search,
  currentPage,
  paginationLinks,
  loadUsers,
  deleteUser,
  formatPageLabel,
  goToPage,
} = useUsers();

const selectedUser = ref(null);
const showFormModal = ref(false);
const showStatusModal = ref(false);
const statusMessage = ref("");
const confirmState = ref({ open: false, user: null });
const futureIdeas = [
  {
    title: "Import users",
    detail: "Upload CSV or Excel files and map columns before saving.",
  },
  {
    title: "Export users",
    detail: "Download filtered lists or selected users as CSV.",
  },
  {
    title: "Bulk actions",
    detail: "Apply delete, status changes, or role updates in one step.",
  },
  {
    title: "Advanced filters",
    detail: "Save searches by role, activity, or last updated date.",
  },
  {
    title: "Audit log",
    detail: "Track who changed what and when for compliance.",
  },
  {
    title: "Permissions",
    detail: "Fine-grained access control for create, edit, and delete.",
  },
];

const handleSaved = async ({ message }) => {
  statusMessage.value = message;
  showStatusModal.value = true;
  selectedUser.value = null;
  showFormModal.value = false;
  await loadUsers(currentPage.value);
};

const handleDelete = async (user) => {
  try {
    await deleteUser(user.id);
    statusMessage.value = "User deleted successfully.";
    showStatusModal.value = true;
    await loadUsers(currentPage.value);
  } catch {
    statusMessage.value = "Delete failed. Please try again.";
    showStatusModal.value = true;
  }
};

const handleEdit = (user) => {
  selectedUser.value = { ...user };
  showFormModal.value = true;
};

const clearSelection = () => {
  selectedUser.value = null;
  showFormModal.value = false;
};

const openCreate = () => {
  selectedUser.value = null;
  showFormModal.value = true;
};

const confirmDelete = (user) => {
  confirmState.value = { open: true, user };
};

const cancelDelete = () => {
  confirmState.value = { open: false, user: null };
};

const approveDelete = async () => {
  const user = confirmState.value.user;
  confirmState.value = { open: false, user: null };
  if (user) {
    await handleDelete(user);
  }
};

onMounted(loadUsers);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                User Management
            </h2>
        </template>

        <div class="py-8 font-['Space_Grotesk'] text-slate-900">
            <div class="mx-auto max-w-[1280px] space-y-8 px-6">
                <header
                    class="flex flex-wrap items-center justify-between gap-6 rounded-2xl border border-slate-200 bg-white p-8 shadow-[0_10px_30px_rgba(15,23,42,0.08)]"
                >
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-500">
                            Admin Dashboard
                        </p>
                        <h1 class="font-['Source_Serif_4'] text-[clamp(2.4rem,3vw,3rem)]">
                            User Management CRUD
                        </h1>
                        <p class="mt-2 max-w-[520px] text-slate-600">
                            Manage users with validation-aware UX and bulk
                            actions.
                        </p>
                    </div>
                    <div class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-amber-100">
                        Laravel 12 + Inertia + Vue
                    </div>
                </header>

                <main class="grid gap-8">
                    <section
                        class="rounded-[18px] border border-slate-200 bg-white p-6 shadow-[0_14px_32px_rgba(15,23,42,0.08)]"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="font-['Source_Serif_4'] text-2xl">Users</h2>
                            <span
                                v-if="meta"
                                class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700"
                            >
                                {{ meta.total }} total
                            </span>
                        </div>
                        <div class="mb-4 flex flex-wrap items-center gap-3">
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search by name or email"
                                class="w-full max-w-[420px] rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            />
                            <button
                                class="ml-auto inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5"
                                type="button"
                                @click="openCreate"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                                    <path d="M11 5h2v14h-2zM5 11h14v2H5z" fill="currentColor" />
                                </svg>
                                New user
                            </button>
                        </div>
                        <div class="max-h-[520px] overflow-y-auto pr-2">
                            <UserList
                                :users="users"
                                :loading="loading"
                                :error="loadError"
                                @edit="handleEdit"
                                @delete="confirmDelete"
                                @retry="loadUsers(currentPage)"
                            />
                        </div>
                        <div v-if="paginationLinks.length" class="mt-4 flex flex-wrap gap-2">
                            <button
                                v-for="link in paginationLinks"
                                :key="link.label"
                                class="rounded-full bg-slate-200 px-3 py-2 text-sm font-semibold text-slate-900 transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
                                type="button"
                                :disabled="!link.url || link.active"
                                @click="goToPage(link.url)"
                            >
                                {{ formatPageLabel(link.label) }}
                            </button>
                        </div>
                    </section>

                    <section class="rounded-[18px] border border-slate-200 bg-slate-50 p-6 shadow-[0_12px_28px_rgba(15,23,42,0.05)]">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="font-['Source_Serif_4'] text-2xl">Future ideas</h2>
                            <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                {{ futureIdeas.length }} items
                            </span>
                        </div>
                        <div class="grid grid-cols-1 gap-4 mt-3 sm:grid-cols-2 lg:grid-cols-3">
                            <article
                                v-for="idea in futureIdeas"
                                :key="idea.title"
                                class="rounded-[14px] border border-orange-200 bg-gradient-to-br from-orange-50 to-amber-100 p-4 shadow-[0_10px_24px_rgba(15,23,42,0.08)]"
                            >
                                <p class="m-0 text-[1.05rem] font-bold text-slate-900">
                                    {{ idea.title }}
                                </p>
                                <p class="mt-1 text-slate-600">
                                    {{ idea.detail }}
                                </p>
                            </article>
                        </div>
                    </section>
                </main>

                <BaseModal
                    :open="showFormModal"
                    :title="selectedUser ? 'Edit User' : 'Create User'"
                    size="lg"
                    @close="clearSelection"
                >
                    <UserForm
                        :selected-user="selectedUser"
                        @saved="handleSaved"
                        @cancel="clearSelection"
                    />
                </BaseModal>

                <BaseModal
                    :open="confirmState.open"
                    title="Confirm delete"
                    size="sm"
                    @close="cancelDelete"
                >
                    <p class="text-slate-700">
                        Are you sure you want to delete
                        <strong>
                            {{ confirmState.user?.first_name }}
                            {{ confirmState.user?.last_name }}
                        </strong>
                        ?
                    </p>
                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            class="rounded-full bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:-translate-y-0.5"
                            type="button"
                            @click="cancelDelete"
                        >
                            Cancel
                        </button>
                        <button
                            class="rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700 transition hover:-translate-y-0.5"
                            type="button"
                            @click="approveDelete"
                        >
                            Delete
                        </button>
                    </div>
                </BaseModal>

                <BaseModal
                    :open="showStatusModal"
                    title="Update"
                    size="sm"
                    @close="showStatusModal = false"
                >
                    <p class="text-slate-700">{{ statusMessage }}</p>
                    <div class="mt-4 flex justify-end">
                        <button
                            class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5"
                            type="button"
                            @click="showStatusModal = false"
                        >
                            Got it
                        </button>
                    </div>
                </BaseModal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
