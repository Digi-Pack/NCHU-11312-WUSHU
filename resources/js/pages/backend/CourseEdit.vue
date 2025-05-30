<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, useForm, router } from '@inertiajs/vue3';
import CourseForm from '@/components/CourseForm.vue';
import Swal from 'sweetalert2';
import { ref, computed, watchEffect } from 'vue';
import ChapterFormModal from '@/components/ChapterFormModal.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';

// 接收 props
const props = defineProps({
  course: Object,
  categories: Object,
  chapters: Object,
});

const page = usePage();
const showModal = ref(false);
const currentChapter = ref(null);
const course = computed(() => page.props.course);
const categories = computed(() => page.props.categories);
const chapters = computed(() => page.props.chapters);

const openCreateModal = () => {
  currentChapter.value = null;
  showModal.value = true;
};

const openEditModal = (chapter) => {
  currentChapter.value = { ...chapter };
  showModal.value = true;
};

const closeModal = () => {
  currentChapter.value = null;
  showModal.value = false;
};
const deleteBtn = async (id) => {
  const confirmed = await useConfirmDialog();
  if (confirmed) {
    router.get(route('admin.chapter.delete', id));
  }
};
// 顯示 flash 訊息
watchEffect(() => {
  const success = page.props.flash?.success;
  const error = page.props.flash?.error;

  if (success) {
    Swal.fire({
      title: success,
      icon: 'success',
      confirmButtonText: '確定',
    }).then(() => {
      window.location.reload();
    });
  }

  if (error) {
    Swal.fire({
      title: '錯誤',
      text: error,
      icon: 'error',
      confirmButtonText: '確定',
    });
  }
});

function goBack() {
  router.get(route('admin.course.list'));
}
</script>

<template>
  <AppLayout title="編輯子課程">
    <nav class="mb-4 flex w-full items-center gap-3 border-b py-2 px-12 ">
      <button type="button" class="rounded-sm bg-darkGray px-4 py-2 text-white hover:bg-stone-600" @click="goBack">返回</button>
      <h1 class="text-2xl font-bold">編輯子課程</h1>
    </nav>
    <div class="py-5 px-12 ">
      <CourseForm :course="course" :categories="categories" />
    </div>
    <hr />
    <div class="space-y-3 py-5 px-12 ">
      <!-- 課程章節列表 -->
      <button type="button" class="mb-4 rounded border  hover:bg-stone-700 border-stone-400 px-4 py-2 bg-darkGray text-white" @click="openCreateModal">新增課程章節</button>
      <table class="min-w-full table-auto border-collapse">
        <thead class="bg-darkGray text-white">
          <tr>
            <th class="border px-4 py-2">排序</th>
            <th class="border px-4 py-2">名稱</th>
            <th class="border px-4 py-2">章節簡介</th>
            <th class="border px-4 py-2">課程影片</th>
            <th class="border px-4 py-2">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="chapters.length === 0">
            <td class="border px-4 py-2 text-center" colspan="5">尚未新增課程章節</td>
          </tr>
          <!-- 使用 v-for 迭代章節資料 -->
          <tr v-for="chapter in chapters" :key="chapter.id">
            <td class="border px-4 py-2">{{ chapter.sort }}</td>
            <td class="border px-4 py-2">{{ chapter.name }}</td>
            <td class="border px-4 py-2">{{ chapter.introduction }}</td>
            <td class="border px-4 py-2">{{ chapter.video_url }}</td>
            <td class="flex flex-wrap gap-2 border px-4 py-2">
              <!-- 編輯與刪除按鈕 -->
              <button @click="deleteBtn(chapter.id)" class="cursor-pointer rounded border border-red-500 px-2 text-red-500 hover:bg-red-500 hover:text-white">刪除</button>
              <button @click="openEditModal(chapter)" class="cursor-pointer rounded border bg-deepTeal px-2 text-white hover:bg-teal-700">編輯</button>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- 新增/編輯 共用 Modal -->
      <ChapterFormModal v-if="showModal" :chapter="currentChapter" :course_id="course.id" @close="closeModal" />
    </div>
  </AppLayout>
</template>
