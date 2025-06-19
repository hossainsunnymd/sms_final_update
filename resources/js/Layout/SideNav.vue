<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const isMobileSidebarOpen = ref(false)
const sidebarNavRef = ref(null)

let savedScrollTop = 0

const toggleMobileSidebar = () => {
  isMobileSidebarOpen.value = !isMobileSidebarOpen.value
}

const closeMobileSidebar = () => {
  if (window.innerWidth < 768) {
    isMobileSidebarOpen.value = false
  }
}

const currentUrl = computed(() => page.url)

const isActiveRoute = (route) => {
  return currentUrl.value.startsWith(route)
}

const handleResize = () => {
  if (window.innerWidth >= 768 && isMobileSidebarOpen.value) {
    isMobileSidebarOpen.value = false;
  }
};

onMounted(() => {
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});

const saveScrollPosition = () => {
  if (sidebarNavRef.value) {
    savedScrollTop = sidebarNavRef.value.scrollTop
  }
}

const restoreScrollPosition = () => {
  nextTick(() => {
    if (sidebarNavRef.value) {
      sidebarNavRef.value.scrollTop = savedScrollTop
    }
  })
}

// Watch for URL changes to restore scroll position
watch(() => page.url, (newUrl, oldUrl) => {
  restoreScrollPosition()
})

</script>

<template>
  <div class="d-flex min-vh-100 bg-light">

    <button
      @click="toggleMobileSidebar"
      class="btn btn-dark d-md-none position-fixed top-0 start-0 m-3 z-3 shadow"
      aria-label="Toggle sidebar"
    >
      <span class="material-icons text-white">menu</span>
    </button>

    <aside
      :class="['bg-white text-dark d-flex flex-column sidebar', { 'sidebar-mobile-open': isMobileSidebarOpen }]"
      style="width: 250px; position: fixed; top: 0; bottom: 0; z-index: 11; transition: transform 0.3s ease-in-out;"
    >
      <div class="p-2 border-bottom">
        <img class="h-[80px]" src=".././Assets/img/logo.jpg" alt="Logo" />
      </div>

      <div class="p-3 border-bottom">
        <h4 class="text-dark">Hello, {{ page.props.user.user_name }}</h4>
      </div>

      <nav
        ref="sidebarNavRef"
        class="flex-grow-1 overflow-auto"
      >
        <ul class="list-unstyled px-2 mt-3">

          <li v-if="page.props.user.can['list-user']" >
            <Link
              href="/list-user"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/list-user') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">groups</span>
              <span>Users</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-role']">
            <Link
              href="/list-role"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/list-role') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">admin_panel_settings</span>
              <span>Roles</span>
            </Link>
          </li>

          <li>
            <Link
              href="/product-stock-list-page"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/product-stock-list') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">inventory</span>
              <span>Product Stock List</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-category']">
            <Link
              href="/list-category"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/list-category') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">category</span>
              <span>Category</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-product']">
            <Link
              href="/list-product-page"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/list-product') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">inventory</span>
              <span>Products</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-requisition']">
            <Link
              href="/list-requisition"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/list-requisition') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">shopping_cart</span>
              <span>Requisitions</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['receive-requisition']">
            <Link
              href="/requisition-product-list"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/requisition-product-list') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">receipt</span>
              <span>Receive Requisition Products</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['approve-reuisition-receive']">
            <Link
              href="/requisition-received-request-list"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/requisition-received-request-list') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons text-green text-success">check_circle</span>
              <span>Approve Requisition</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['approve-floor-receive']">
            <Link
              href="/floor-receive-list"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/floor-receive-list') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons text-green text-success">check_circle</span>
              <span>Approve Floor Recieve</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-issue-product']">
            <Link
              href="/issue-product-list"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/issue-product-list') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">remove_shopping_cart</span>
              <span>Product Issue List</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-vendor']">
            <Link
              href="/list-vendor"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/list-vendor') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">store</span>
              <span>Vendors</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-purchase']">
            <Link
              href="/list-purchase"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/list-purchase') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">shopping_bag</span>
              <span>Purchases</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-minimum-product']">
            <Link
              href="/minimum-product-list"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/minimum-product-list') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">warning</span>
              <span>Minimum Stock</span>
            </Link>
          </li>

          <li v-if="page.props.user.can['list-damage-product']">
            <Link
              href="/damage-product-list"
              :class="['d-flex align-items-center gap-2 px-3 py-2 rounded', isActiveRoute('/damage-product-list') ? 'bg-light fw-bold' : 'text-dark']"
              @click="() => { saveScrollPosition(); closeMobileSidebar() }"
              preserve-scroll
            >
              <span class="material-icons">dangerous</span>
              <span>Damaged Stock List</span>
            </Link>
          </li>

        </ul>
      </nav>

      <div class="p-3 border-top border-secondary">
        <Link href="/logout" class="btn btn-danger w-100">
          Logout
        </Link>
      </div>
    </aside>

    <main
      class="flex-grow-1 p-4 main-content"
      style="min-height: 100vh; overflow-y: auto;"
    >
      <div class="bg-white rounded shadow p-4">
        <slot></slot>
      </div>
    </main>
  </div>
</template>

<style scoped>
.sidebar {
  transform: translateX(0);
}

.main-content {
  margin-left: 250px;
}

@media (max-width: 767.98px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar-mobile-open {
    transform: translateX(0);
  }

  .main-content {
    margin-left: 0 !important;
  }

  .sidebar-mobile-open + .main-content::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10;
  }
}
</style>
