<script setup>
import { ref } from 'vue';

const tab = ref('signup');  // 初始選擇註冊 Tab
const name = ref('');
const phone = ref('');
const birthDate = ref('');
const birthTime = ref('');
const birthCity = ref('');
const address = ref('');
const email = ref('');
const password = ref('');
const termsAccepted = ref(false);

// 登入表單的變數
const loginEmail = ref('');
const loginPassword = ref('');

// 選到的按鈕樣式
const activeTabClass = 'bg-[#1F9C95] text-white border border-[#1F9C95]';
// 沒選到的按鈕樣式
const inactiveTabClass = 'bg-white text-[#1F9C95] border border-[#1F9C95]';

// 註冊請求
const register = async () => {
  if (!termsAccepted.value) {
    alert('請同意條款與隱私權政策');
    return;
  }

  try {
    const response = await fetch('/wushu/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: name.value,
        phone: phone.value,
        birth_date: birthDate.value,
        birth_time: birthTime.value,
        birth_city: birthCity.value,
        address: address.value,
        email: email.value,
        password: password.value,
      }),
    });

    if (response.ok) {
      // 註冊成功後跳轉到登入頁面
      window.location.href = '/wushu/LoginSignup';
    } else {
      const errorData = await response.json();
      alert(errorData.message || '註冊失敗，請稍後再試');
    }
  } catch (error) {
    console.error('註冊請求發生錯誤', error);
    alert('註冊請求發生錯誤');
  }
};

// 登入請求
const login = async () => {
  try {
    const response = await fetch('/wushu/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email: loginEmail.value,
        password: loginPassword.value,
      }),
    });

    if (response.ok) {
      // 登入成功，重定向
      window.location.href = '/wushu/member-center';
    } else {
      const errorData = await response.json();
      alert(errorData.message || '登入失敗，請檢查帳號密碼');
    }
  } catch (error) {
    console.error('登入請求發生錯誤', error);
    alert('登入請求發生錯誤');
  }
};
</script>


<template>
  <header class="flex h-[234px] w-full flex-col items-center bg-black sm:h-[303px] xl:h-[444px]">
    <p class="pt-[120px] font-serif text-[32px]/[46px] font-bold text-grayWhite sm:pt-[160px] sm:text-[52px]/[75px] xl:pt-[224px] xl:text-[64px]/[92px]">
      登入註冊
    </p>
    <span class="w-[120px] border-b-2 border-grayWhite sm:w-[300px]"></span>
  </header>

  <div class="bg-white min-h-screen flex justify-center my-20">
    <div class="container mx-auto px-4">
      <Header />

      <!-- Flex 容器：包含 Tabs + 卡片 -->
      <div class="flex flex-col items-center gap-4">
        <!-- Tabs 在卡片上方，左右對齊 -->
        <div class="flex justify-between items-center w-full max-w-md">
          <button @click="tab = 'login'" :class="tab === 'login' ? activeTabClass : inactiveTabClass"
            class="text-2xl px-16 py-3 rounded-md focus:outline-none transition-colors duration-300 whitespace-nowrap">
            登入
          </button>

          <div class="w-px h-12 bg-gray-300"></div>

          <button @click="tab = 'signup'" :class="tab === 'signup' ? activeTabClass : inactiveTabClass"
            class="text-2xl px-16 py-3 rounded-md focus:outline-none transition-colors duration-300 whitespace-nowrap">
            註冊
          </button>
        </div>

        <div class="w-full max-w-md mx-auto bg-white rounded-lg overflow-hidden border border-gray-00 shadow-2xl">
          <!-- Content -->
          <div class="p-8">
            <!-- Signup Form -->
            <form v-if="tab === 'signup'" class="space-y-6" @submit.prevent="register">
              <h2 class="text-3xl font-bold mb-6">個人資料</h2>

              <!-- 姓名 -->
              <div class="relative">
                <input v-model="name" type="text" id="name" placeholder=" "
                  class="peer w-full px-4 py-3 border border-gray-400 rounded-md focus:outline-none" />
                <label for="name" class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-[-8px] peer-focus:text-xs bg-white px-1">
                  姓名
                </label>
              </div>

              <!-- 手機號碼 -->
              <div class="relative">
                <input v-model="phone" type="tel" id="phone" placeholder=" "
                  class="peer w-full px-4 py-3 border border-gray-400 rounded-md focus:outline-none" />
                <label for="phone" class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-[-8px] peer-focus:text-xs bg-white px-1">
                  手機號碼
                </label>
              </div>

              <!-- 出生日期和出生時間 -->
              <div class="flex flex-col sm:flex-row sm:space-x-4">
                <div class="relative flex-1">
                  <input v-model="birthDate" type="date" id="birth-date" placeholder=" "
                    class="peer w-full px-4 py-3 border border-gray-400 rounded-md focus:outline-none" />
                  <label for="birth-date" class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-[-8px] peer-focus:text-xs bg-white px-1">
                    出生日期
                  </label>
                </div>

                <div class="relative flex-1 mt-4 sm:mt-0">
                  <input v-model="birthTime" type="time" id="birth-time" placeholder=" "
                    class="peer w-full px-4 py-3 border border-gray-400 rounded-md focus:outline-none" />
                  <label for="birth-time" class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-[-8px] peer-focus:text-xs bg-white px-1">
                    出生時間
                  </label>
                </div>
              </div>

              <!-- 出生城市 -->
              <div class="relative">
                <input v-model="birthCity" type="text" id="birth-city" placeholder=" "
                  class="peer w-full px-4 py-3 border border-gray-400 rounded-md focus:outline-none" />
                <label for="birth-city" class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-[-8px] peer-focus:text-xs bg-white px-1">
                  出生城市
                </label>
              </div>

              <!-- 地址 -->
              <div class="relative">
                <input v-model="address" type="text" id="address" placeholder=" "
                  class="peer w-full px-4 py-3 border border-gray-400 rounded-md focus:outline-none" />
                <label for="address" class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-focus:top-[-8px] peer-focus:text-xs bg-white px-1">
                  地址
                </label>
              </div>

              <h2 class="text-3xl font-bold mt-8 mb-6">電子郵件</h2>
              <p class="text-gray-600 mb-4">此信箱同時作為登入帳號使用。</p>

              <!-- 電子郵件 -->
              <div class="flex flex-col">
                <input v-model="email" type="email" placeholder="請輸入電子郵件"
                  class="w-full px-4 py-3 border rounded-md focus:outline-none" />
              </div>

              <h2 class="text-3xl font-bold mt-8 mb-6">密碼</h2>

              <!-- 密碼 -->
              <div class="flex flex-col">
                <input v-model="password" type="password" placeholder="密碼/至少6碼/英數混和"
                  class="w-full px-4 py-3 border rounded-md focus:outline-none" />
              </div>

              <!-- 同意條款 -->
              <div class="flex items-start space-x-2 mt-4">
                <input v-model="termsAccepted" type="checkbox" id="terms" class="w-5 h-5 text-[#1F9C95]  border-gray-400 rounded mt-1">
                <label for="terms" class="text-gray-600 text-base">
                  同意本站的
                  <a href="http://127.0.0.1:8001/wushu/Policy" target="_blank"
                    class="text-blue-500 hover:underline">付款退款條款</a> 與
                  <a href="http://127.0.0.1:8001/wushu/Privacy" target="_blank"
                    class="text-blue-500 hover:underline">隱私權政策</a>。
                </label>
              </div>

              <!-- 註冊按鈕 -->
              <button type="submit"
                class="w-full bg-[#1F9C95] text-white text-xl py-3 rounded-md hover:opacity-90 transition-opacity duration-300 transform hover:scale-105 mt-6">
                註冊會員
              </button>
            </form>

            <!-- Login Form -->
            <form v-else class="space-y-6" @submit.prevent="login">
              <!-- Email -->
              <div class="flex flex-col">
                <label class="text-2xl font-semibold text-gray-700 mb-2">帳號</label>
                <div class="relative">
                  <input v-model="loginEmail" type="text" placeholder="帳號"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none pl-10" />
                  <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                </div>
              </div>

              <!-- Password -->
              <div class="flex flex-col">
                <label class="text-2xl font-semibold text-gray-700 mb-2">密碼</label>
                <div class="relative">
                  <input v-model="loginPassword" type="password" placeholder="密碼"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none pl-10" />
                  <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                </div>
              </div>

              <!-- 忘記密碼 -->
              <div class="flex justify-start">
                <a href="#" class="text-gray-400 text-lg hover:underline">忘記密碼？</a>
              </div>

              <!-- 登入按鈕 -->
              <button type="submit"
                class="w-full bg-[#518C95] text-white text-lg py-3 rounded-md hover:opacity-90 transition-opacity duration-300 transform hover:scale-105">
                登入會員
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<!-- Input component with icon (reusable) -->

<!-- FontAwesome & Tailwind -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
}

input[type="date"]::-webkit-datetime-edit {
  color: transparent;
}

input[type="date"]:focus::-webkit-datetime-edit {
  color: black;
  /* 聚焦時再顯示正常文字 */
}
</style>

<!-- External Links (place in index.html or main layout) -->
<!--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
-->
