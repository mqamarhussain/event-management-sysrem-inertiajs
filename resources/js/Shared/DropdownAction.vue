<template>
  <div class="relative text-center" ref="dropdownContainer">
    <button
      id="dropdownMenuIconButton"
      @click="toggleDropdown"
      class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50"
      type="button"
    >
      <slot name="button-content">
        <svg
          class="w-5 h-5"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          viewBox="0 0 4 15"
        >
          <path
            d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"
          />
        </svg>
      </slot>
    </button>

    <!-- Dropdown menu -->
    <div
      v-if="dropdownOpen"
      id="dropdownDots"
      class="absolute right-0 mt-2 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44"
    >
      <div class="py-2">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dropdownOpen: false,
    };
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
      if (this.dropdownOpen) {
        document.addEventListener("click", this.handleClickOutside);
      } else {
        document.removeEventListener("click", this.handleClickOutside);
      }
    },
    handleClickOutside(event) {
      if (!this.$refs.dropdownContainer.contains(event.target)) {
        this.dropdownOpen = false;
        document.removeEventListener("click", this.handleClickOutside);
      }
    },
  },
};
</script>
