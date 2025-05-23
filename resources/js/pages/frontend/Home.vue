<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { usePage } from '@inertiajs/vue3'

import Header from '../../components/Header.vue'
import Footer from '../../components/Footer.vue'
import BackToTop from '../../components/BackToTop.vue'


const page = usePage()
const categories = computed(() => page.props.categories || [])
const coursesByCategory = computed(() => page.props.coursesByCategory || {})
const featuredCourses = computed(() => page.props.featuredCourses || [])

// ====== 載入圖片資源 ======
import imgSrc from '@/images/homem.webp'
import courseImg1 from '@/images/主打課程1a2axq-d0uqq.webp'
import courseImg2 from '@/images/主打課程2a1t2a-q5lk4.webp'
import masterImg1 from '@/images/homes.webp'
import masterImg2 from '@/images/homel.webp'
import tarotImg from '@/images/home-dqrfv.webp'
import tcmImg from '@/images/ay1lo-pmcep.webp'
import magicImg from '@/images/home-dqrfv.webp'
import othersImg from '@/images/FengShui.webp'

// ====== 背景圖 ======
const bgImage = imgSrc

// ====== 響應式裝置判斷 ======
const screens = {
    sm: '600px',
    xl: '1270px'
}

const windowWidth = ref(window.innerWidth)

const isDesktop = computed(() => windowWidth.value >= parseInt(screens.xl))
const isTablet = computed(() => windowWidth.value < parseInt(screens.xl) && windowWidth.value >= parseInt(screens.sm))
const isMobile = computed(() => windowWidth.value < parseInt(screens.sm))

const handleResize = () => {
    windowWidth.value = window.innerWidth
}

// ====== Tabs 資料 ======

const tabs = computed(() => {

    const categoryImages = {
        '吠陀占星': tarotImg,
        '中醫': tcmImg,
        '古典魔法': magicImg,
        '其它術數': othersImg
    }

    return categories.value.map(category => {
        return {
            name: category.slug || category.id.toString(),
            label: category.name,
            image: categoryImages[category.name] || othersImg
        }
    })
})

const activeTab = ref(tabs.value.length > 0 ? tabs.value[0].name : '')

const currentImage = computed(() => {
    const tab = tabs.value.find(t => t.name === activeTab.value)
    return tab ? tab.image : ''
})

// ====== 分頁設定 ======
const coursesPerPage = 6
const currentPage = ref(1)

const getCoursesForTab = (tabName) => {

    const category = categories.value.find(c => c.slug === tabName || c.id.toString() === tabName)
    if (category && coursesByCategory.value[category.name]) {
        return coursesByCategory.value[category.name]
    }
    return []
}

const totalPages = computed(() => {
    const coursesForActiveTab = getCoursesForTab(activeTab.value)
    return Math.ceil(coursesForActiveTab.length / coursesPerPage)
})

const paginatedCourses = computed(() => {
    const coursesForActiveTab = getCoursesForTab(activeTab.value)
    const start = (currentPage.value - 1) * coursesPerPage
    const end = start + coursesPerPage
    return coursesForActiveTab.slice(start, end)
})

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
    }
}

const changeTab = (tabName) => {
    activeTab.value = tabName
    currentPage.value = 1
}

// ====== LifeCycle Hooks ======
onMounted(() => {
    window.addEventListener('resize', handleResize)
    handleResize()


    if (tabs.value.length > 0 && !activeTab.value) {
        activeTab.value = tabs.value[0].name
    }

    // 預載所有圖片
    tabs.value.forEach(tab => {
        const img = new Image()
        img.src = tab.image
    })
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize)
})
</script>





<template>
    <div>
        <Header />

        <main>
            <!-- banner -->
            <section class="bg-cover bg-center bg-no-repeat w-full h-[60vh] sm:h-[70vh] xl:h-[920px]"
                :style="`background-image: url(${bgImage})`">
            </section>

            <section id="intro" class="bg-white py-12 md:py-16 xl:py-36">
                <div class="max-w-7xl mx-auto px-4">

                    <div class="text-center mb-8 md:mb-10 xl:mb-12">
                        <h2 class="text-3xl md:text-4xl xl:text-6xl font-bold text-black font-serif inline-block pb-2">
                            主打課程
                        </h2>
                        <div class="h-0.5 bg-black mt-2 mx-auto w-[180px] md:w-[200px] xl:w-[220px]"></div>
                    </div>
                    <!-- Cards container -->
                    <div class="flex flex-col xl:flex-row gap-6 md:gap-8 justify-center items-center">


                        <div v-for="(course, index) in featuredCourses" :key="course.id"
                            class="bg-white overflow-hidden w-full xl:w-1/2" :class="{ 'mt-6 xl:mt-0': index > 0 }"
                            style="cursor: pointer;" @click="$inertia.visit(`/wushu/CourseIntro/${course.id}`)">
                            <img :src="course.featured_image || (index === 0 ? courseImg1 : courseImg2)"
                                :alt="course.name"
                                class="w-full h-[240px] sm:h-[280px] md:h-[320px] xl:h-[360px] object-cover" />
                            <div class="p-4 md:p-5 xl:p-6">
                                <p
                                    class="text-2xl sm:text-3xl md:text-4xl xl:text-[48px] font-bold text-black font-serif text-left">
                                    {{ course.name }}
                                </p>
                                <div class="mt-4 flex items-center gap-3">
                                    <button
                                        class="px-4 md:px-5 xl:px-6 py-1.5 md:py-2 bg-alertRed text-white rounded-full text-base md:text-lg hover:bg-gray-800 transition duration-300">
                                        {{ course.category.name }}
                                    </button>
                                    <p class="text-xl md:text-2xl xl:text-3xl font-bold text-black">NT${{ course.price
                                        }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- 如果沒有主打課程，則顯示原始硬編碼的內容 -->
                        <template v-if="!featuredCourses || featuredCourses.length === 0">
                            <!-- Card 1 -->
                            <div class="bg-white overflow-hidden w-full xl:w-1/2 cursor-pointer"
                                @click="$inertia.visit('/wushu/CourseIntro/1')">
                                <img :src="courseImg1" alt="蔣氏玄空風水執業班"
                                    class="w-full h-[240px] sm:h-[280px] md:h-[320px] xl:h-[360px] object-cover" />
                                <div class="p-4 md:p-5 xl:p-6">
                                    <p
                                        class="text-2xl sm:text-3xl md:text-4xl xl:text-[48px] font-bold text-black font-serif text-left">
                                        蔣氏玄空風水執業班
                                    </p>
                                    <div class="mt-4 flex items-center gap-3">
                                        <button
                                            class="px-4 md:px-5 xl:px-6 py-1.5 md:py-2 bg-alertRed text-white rounded-full text-base md:text-lg hover:bg-gray-800 transition duration-300">
                                            其他術數
                                        </button>
                                        <p class="text-xl md:text-2xl xl:text-3xl font-bold text-black">NT$5,000</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="bg-white overflow-hidden w-full xl:w-1/2 mt-6 xl:mt-0 cursor-pointer"
                                @click="$inertia.visit('/wushu/CourseIntro/2')">
                                <img :src="courseImg2" alt="康治本傷寒論"
                                    class="w-full h-[240px] sm:h-[280px] md:h-[320px] xl:h-[360px] object-cover" />
                                <div class="p-4 md:p-5 xl:p-6">
                                    <p
                                        class="text-2xl sm:text-3xl md:text-4xl xl:text-[48px] font-bold text-black font-serif text-left">
                                        康治本傷寒論
                                    </p>
                                    <div class="mt-4 flex items-center gap-3">
                                        <button
                                            class="px-4 md:px-5 xl:px-6 py-1.5 md:py-2 bg-alertRed text-white rounded-full text-base md:text-lg hover:bg-gray-800 transition duration-300">
                                            中醫
                                        </button>
                                        <p class="text-xl md:text-2xl xl:text-3xl font-bold text-black">NT$5,000</p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>

            <!-- Courses offered -->
            <section class="relative w-full bg-black text-white overflow-hidden min-h-[858px]">

                <div class="absolute inset-0 z-0 overflow-hidden">
                    <transition name="fade" mode="out-in">
                        <img :key="activeTab" :src="currentImage" alt="課程背景"
                            class="w-full h-full object-contain object-left transition-opacity duration-500"
                            :class="activeTab === 'medicine' ? 'opacity-90' : 'opacity-50'" />

                    </transition>
                    <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
                </div>

                <div class="container mx-auto relative z-10">

                    <template v-if="isDesktop">
                        <div class="flex">

                            <div class="w-1/3 relative overflow-hidden bg-transparent pt-10"
                                style="margin-top: 20px; margin-left: 10px;">

                                <div class="relative z-10 px-6">
                                    <h2 class="text-[64px] font-serif font-bold">開立課程</h2>
                                    <div class="h-0.5 bg-white mt-2 w-60"></div>
                                    <a href="/wushu/ServiceCourse">
                                        <button
                                            class="w-36 h-14 mt-6 px-6 py-2 bg-deepTeal text-white rounded text-[24px] hover:bg-teal-700 transition duration-300">
                                            了解更多
                                        </button>
                                    </a>
                                </div>
                            </div>


                            <div class="w-2/3 flex flex-col pt-14 pr-6 pl-10">

                                <div class="flex space-x-6 pb-4 border-b border-gray-700 overflow-x-auto">
                                    <button v-for="tab in tabs" :key="tab.name" class="py-2.5 px-6 font-medium 
                font-[Microsoft JhengHei] transition duration-200 whitespace-nowrap text-[32px]"
                                        :class="{ 'text-blueGreen border-b-2 border-blueGreen': activeTab === tab.name, 'text-gray-300 hover:text-white': activeTab !== tab.name }"
                                        @click="changeTab(tab.name)">
                                        {{ tab.label }}
                                    </button>
                                </div>


                                <div class="flex-1 py-6 space-y-0">
                                    <div v-for="course in paginatedCourses" :key="course.id"
                                        class="flex items-center justify-between py-3.5 border-b border-dotted border-gray-600">
                                        <div class="text-[32px] font-medium w-2/5 truncate pr-4">
                                            {{ course.name }}
                                        </div>
                                        <div class="flex items-center justify-end space-x-4 w-3/5">
                                            <span
                                                class="bg-deepTeal text-white text-[24px] px-5 py-2 rounded-full w-32 flex justify-center items-center text-center">
                                                {{ course.lessons || 0 }}堂課
                                            </span>
                                            <span class="text-gray-300 w-24 text-[24px] text-center">{{ course.duration
                                                || '2小時/堂' }}</span>
                                            <span class="text-deepTeal font-bold text-[24px] w-28 text-right">NT${{
                                                course.price }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 分頁按鈕 -->
                                <div class="flex items-center space-x-2 mt-4">
                                    <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                                        class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-white rounded disabled:opacity-50">
                                        ‹
                                    </button>

                                    <button v-for="page in totalPages" :key="page" @click="changePage(page)" :class="[
                                        'w-8 h-8 flex items-center justify-center rounded',
                                        page === currentPage ? 'bg-deepTeal text-white' : 'text-gray-300 hover:text-white'
                                    ]">
                                        {{ page }}
                                    </button>

                                    <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                                        class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-white rounded disabled:opacity-50">
                                        ›
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- 平板版 -->
                    <template v-else-if="isTablet">

                        <div class="text-center pt-10 pb-6">
                            <h2 class="text-[48px] font-serif font-bold">開立課程</h2>
                            <div class="h-0.5 bg-white mt-2 w-60 mx-auto"></div>
                            <a href="/wushu/ServiceCourse" class="block mt-6">
                                <button
                                    class="w-36 h-14 px-6 py-2 bg-deepTeal text-white text-nowrap rounded text-[24px] hover:bg-teal-700 transition duration-300">
                                    了解更多
                                </button>
                            </a>
                        </div>


                        <div class="flex justify-center space-x-6 pb-4 border-b border-gray-700 overflow-x-auto px-4">
                            <button v-for="tab in tabs" :key="tab.name"
                                class="py-2 px-4 font-medium text-2xl whitespace-nowrap"
                                :class="{ 'text-blueGreen border-b-2 border-blueGreen': activeTab === tab.name, 'text-gray-300 hover:text-white': activeTab !== tab.name }"
                                @click="changeTab(tab.name)">
                                {{ tab.label }}
                            </button>
                        </div>


                        <div class="py-6 px-6">
                            <div v-for="course in paginatedCourses" :key="course.id"
                                class="flex items-center justify-between py-3.5 border-b border-dotted border-gray-600">
                                <div class="text-2xl font-medium w-1/3 truncate pr-2">
                                    {{ course.name }}
                                </div>
                                <div class="flex items-center justify-end gap-2 w-2/3">
                                    <span
                                        class="bg-deepTeal text-white text-xl px-4 py-1 rounded-full whitespace-nowrap w-24 flex justify-center items-center text-center">
                                        {{ course.lessons || 0 }}堂課
                                    </span>

                                    <span class="text-gray-300 text-xl text-center whitespace-nowrap">{{ course.duration
                                        || '2小時/堂' }}</span>
                                    <span class="text-deepTeal font-bold text-xl text-right">NT${{ course.price
                                        }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 分頁按鈕 -->
                        <div class="flex items-center justify-center space-x-2 mt-4 pb-6">
                            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                                class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-white rounded disabled:opacity-50">
                                ‹
                            </button>

                            <button v-for="page in totalPages" :key="page" @click="changePage(page)" :class="[
                                'w-8 h-8 flex items-center justify-center rounded',
                                page === currentPage ? 'bg-deepTeal text-white' : 'text-gray-300 hover:text-white'
                            ]">
                                {{ page }}
                            </button>

                            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                                class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-white rounded disabled:opacity-50">
                                ›
                            </button>
                        </div>
                    </template>

                    <!-- 手機版：垂直佈局 -->
                    <template v-else>
                        <!-- 標題區 -->
                        <div class="text-center pt-10 pb-6">
                            <h2 class="text-4xl font-serif font-bold">開立課程</h2>
                            <div class="h-0.5 bg-white mt-2 w-40 mx-auto"></div>
                            <a href="/wushu/ServiceCourse" class="block mt-4">
                                <button
                                    class="w-32 h-12 px-5 py-2 bg-deepTeal text-white rounded text-xl hover:bg-teal-700 transition duration-300">
                                    了解更多
                                </button>
                            </a>
                        </div>


                        <div class="flex overflow-x-auto pb-3 border-b border-gray-700 px-4">
                            <button v-for="tab in tabs" :key="tab.name"
                                class="py-2 px-4 mr-4 font-medium text-lg whitespace-nowrap flex-shrink-0"
                                :class="{ 'text-blueGreen border-b-2 border-blueGreen': activeTab === tab.name, 'text-gray-300 hover:text-white': activeTab !== tab.name }"
                                @click="changeTab(tab.name)">
                                {{ tab.label }}
                            </button>
                        </div>


                        <div class="px-4 py-4">
                            <div v-for="course in paginatedCourses" :key="course.id"
                                class="border-b border-dotted border-gray-600 py-4">
                                <div class="text-xl font-medium mb-2">{{ course.name }}</div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2 w-full">
                                        <span
                                            class="bg-deepTeal text-white text-sm px-3 py-1 rounded-full w-20 flex justify-center items-center text-center">
                                            {{ course.lessons || 0 }}堂課
                                        </span>
                                        <span
                                            class="text-gray-300 text-sm text-center flex-1 sm:flex-none sm:text-left">
                                            {{ course.duration || '2小時/堂' }}
                                        </span>
                                    </div>
                                    <span class="text-deepTeal font-bold text-lg">NT${{ course.price }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 分頁按鈕 -->
                        <div class="flex items-center justify-center space-x-2 mt-4 pb-6">
                            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                                class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-white rounded disabled:opacity-50">
                                ‹
                            </button>

                            <button v-for="page in totalPages" :key="page" @click="changePage(page)" :class="[
                                'w-8 h-8 flex items-center justify-center rounded',
                                page === currentPage ? 'bg-deepTeal text-white' : 'text-gray-300 hover:text-white'
                            ]">
                                {{ page }}
                            </button>

                            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                                class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-white rounded disabled:opacity-50">
                                ›
                            </button>
                        </div>
                    </template>
                </div>
            </section>





            <!-- 關於站主區塊 -->
            <section id="about-section" class="relative bg-white">
                <div class="max-w-[1200px] mx-auto px-[20px] py-[40px] sm:px-[30px] sm:py-[60px]">
                    <h2
                        class="text-[36px] sm:text-[48px] xl:text-[64px] font-bold text-center mb-[30px] sm:mb-[40px] relative block font-serif after:content-[''] after:absolute after:bottom-[-6px] after:left-1/2 after:-translate-x-1/2 after:w-[180px] sm:after:w-[260px] after:h-[2px] after:bg-black">
                        關於站主
                    </h2>


                    <div class="flex flex-col xl:flex-row items-center gap-[30px] sm:gap-[50px] xl:gap-[40px]">

                        <div class="flex-1 text-[18px] sm:text-[20px] xl:text-[24px]">
                            <p class="leading-[1.8] mb-[40px]">
                                文老師自學生時期便開始學習五術，致力於五術教學十餘年。中醫醫術－針灸傳承自古法針灸、董氏奇穴和山西派鍼法；脈學傳承自萬氏的太素脈學；內科傳承各家如經方、溫病、伏氣學派等多位老師；祝由傳承自西河派和軒轅派。
                            </p>
                            <p class="leading-[1.8] mb-[40px]">
                                命學目前以吠陀占星 Jyotish 為主，師從 Komilla、Joni Patry、Marc Boney
                                等國際大家還有十數位印度吠陀占星大師；風水承襲自蔣氏三元玄空風水、吠陀 Vastu
                                和龍門八局，亦通道家奇門遁甲、大六壬、太乙神數三式之學、梅花易數，以及是為熊氏太極拳傳人及華佗門第八十代傳人。
                            </p>
                            <p class="leading-[1.8] mb-[40px]">
                                另外西方之學尚有西洋古典行星護符魔法、北歐魔法等皆是師從大家。
                            </p>
                        </div>


                        <div class="flex-1 w-full xl:w-auto overflow-hidden mt-14">
                            <img :src="masterImg1" alt="禪石圖" class="w-full h-auto block xl:max-w-[741px]" />
                        </div>
                    </div>
                </div>
            </section>



            <!-- 學會創立沿革區塊 -->
            <section id="history-section" class="relative bg-white pb-14">
                <div class="max-w-[1903px] mx-auto px-5 py-10 sm:px-8 sm:py-16 xl:px-0 xl:py-12">


                    <h2 class="text-[36px] sm:text-[48px] xl:hidden font-bold text-center mb-8 font-serif">
                        <span
                            class="relative inline-block after:content-[''] after:absolute after:bottom-[-8px] after:left-0 after:w-full after:h-[2px] after:bg-black">
                            學會創立沿革
                        </span>
                    </h2>


                    <div class="flex flex-col xl:flex-row items-start">


                        <div class="w-full xl:w-[55%] overflow-hidden">
                            <img :src="masterImg2" alt="中藥櫃" class="w-full h-auto object-contain" />
                        </div>


                        <div
                            class="w-full xl:w-[45%] bg-transparent sm:bg-lightGray px-5 py-24 sm:px-8 flex flex-col items-center mt-8 xl:mt-60 relative">


                            <h2
                                class="hidden xl:block absolute -top-36 left-0 xl:left-[60px] text-[64px] font-bold text-left mb-8 font-serif">
                                <span
                                    class="relative inline-block after:content-[''] after:absolute after:bottom-[-8px] after:left-0 after:w-full after:h-[2px] after:bg-black">
                                    學會創立沿革
                                </span>
                            </h2>



                            <div class="max-w-[800px] w-full">
                                <p class="text-lg sm:text-xl xl:text-2xl leading-[1.8] mb-5">
                                    <span
                                        class="text-3xl sm:text-4xl xl:text-4xl font-bold font-inter text-black">2008</span>年，當時文老師在內湖高中任教，
                                    部分同事和學生知曉文老師傳承自古老的醫術，便欲向其學習，但老師當時並未想開課授徒，一方面是其老師們尚在世，
                                    二方面是覺得自己尚未成熟，故以五術研究社這種讀書會模式帶領眾人學習，不收學費為眾人解惑。
                                </p>
                                <p class="text-lg sm:text-xl xl:text-2xl leading-[1.8]">
                                    直到2014在湖南攻讀中醫內科學研究所，同儕紛紛希望向文老師學習師承，加上傳授針灸和內科給文老師的老師們都過世了，
                                    文老師才開始決定開課陸續至今。
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </section>





        </main>

        <Footer />
        <BackToTop />
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
