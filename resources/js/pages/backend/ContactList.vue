<script setup>
import { ref, computed, watchEffect } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import ContactDetailModal from '@/components/ContactDetailModal.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import TableWithPagination from '@/components/TableWithPagination.vue';
import { useTableController } from '@/composables/useTableController';

// const contacts = page.props.contacts;

//

const { items, currentPage, totalPages, searchQuery, currentSortKey, currentSortDirection, handleSearch, handlePageChange, handleSortChange } =
  useTableController('contacts', 'admin.contact.list'); // 名稱依照 controller 傳的變數與 route 命名

const columns = [
  { label: '日期', key: 'formatted_date', sortable: true },
  { label: '姓名', key: 'name' },
  { label: '主旨', key: 'title' },
  { label: '操作', key: 'actions' },
];

// 個別查看詳細內容
const selectedContact = ref(null);
function openDetail(contact) {
  selectedContact.value = contact;
}

// 按鈕事件
const deleteBtn = async (id) => {
  const confirmed = await useConfirmDialog();
  if (confirmed) {
    router.get(route('admin.contact.delete', id));
  }
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <h1 class="fixed mb-12 w-full bg-white px-12 py-4 text-2xl font-bold shadow">聯絡我們紀錄</h1>
    <div class="mt-20 px-12">
      <TableWithPagination
        :columns="columns"
        :items="items"
        :current-page="currentPage"
        :total-pages="totalPages"
        :sort-key="currentSortKey"
        :sort-direction="currentSortDirection"
        :showSearch="true"
        @search="handleSearch"
        @page-change="handlePageChange"
        @sort-change="handleSortChange"
      >
        <!-- Slot: actions 按鈕 -->
        <template #cell(actions)="{ item }">
          <div class="flex justify-center gap-2">
            <button type="button" class="rounded cursor-pointer border border-red-500 px-2 text-red-500 hover:bg-red-500 hover:text-white" @click="deleteBtn(item.id)">刪除</button>
            <button type="button" class="rounded cursor-pointer border bg-deepTeal px-2 text-white hover:bg-teal-700" @click="openDetail(item)">查看</button>
          </div>
        </template>
      </TableWithPagination>
    </div>
  </AppLayout>
  <ContactDetailModal :contact="selectedContact" @close="selectedContact = null" />
</template>
