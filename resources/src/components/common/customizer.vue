<template>
  <div>
    <div class="customizer" :class="{ open: isOpen }">
      <div class="handle" @click="isOpen = !isOpen">
        <i class="i-Gear spin"></i>
      </div>

      <vue-perfect-scrollbar
        :settings="{ suppressScrollX: true, wheelPropagation: false }"
        class="customizer-body ps rtl-ps-none"
      >
        <div
          class
          v-if="getThemeMode.layout != 'vertical-sidebar' && getThemeMode.layout != 'vertical-sidebar-two'"
        >
          <div class="card-header" id="headingOne">
            <p class="mb-0">RTL</p>
          </div>

          <div class="card-body">
            <label class="checkbox checkbox-primary">
              <input type="checkbox" id="rtl-checkbox" @change="changeThemeRtl" />
              <span>Enable RTL</span>
              <span class="checkmark"></span>
            </label>
          </div>
        </div>

        <div class>
          <div class="card-header">
            <p class="mb-0">Dark Mode</p>
          </div>

          <div class="card-body">
            <label class="switch switch-primary mr-3 mt-2" v-b-popover.hover.left="'Dark Mode'">
              <input type="checkbox" @click="changeThemeMode" />
              <span class="slider"></span>
            </label>
          </div>
        </div>

         <div class>
          <div class="card-header">
            <p class="mb-0">Language</p>
          </div>

          <div class="card-body">
             <div class="menu-icon-language">

                <a v-for="lang in getAvailableLanguages" :key="lang.locale" @click="SetLocal(lang.locale)">
                  <img
                    :src="`/flags/${lang.flag}`"
                    :alt="lang.name"
                    class="flag-icon flag-icon-squared"
                    style="width: 20px; margin-right: 8px"
                  />
                  <span class="title-lang">{{ lang.name }}</span>
                </a>
            
            </div>
          </div>
        </div>
      </vue-perfect-scrollbar>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      isOpen: false,
      languages: [],
    };
  },

  computed: {
    ...mapGetters(["getThemeMode", "getcompactLeftSideBarBgColor", "getAvailableLanguages"]),
  },

  methods: {
    ...mapActions([
      "changeThemeRtl",
      "changeThemeLayout",
      "changeThemeMode",
      "changecompactLeftSideBarBgColor",
    ]),

    SetLocal(locale) {
      this.$i18n.locale = locale;
      this.$store.dispatch("language/setLanguage", locale);
      Fire.$emit("ChangeLanguage");
      window.location.reload();
    },
    
    // async fetchLanguages() {
    //   try {
    //     const response = await axios.get("/languages");
    //     this.languages = response.data;
    //   } catch (error) {
    //     console.warn("Failed to load languages");
    //   }
    // },
  },

  created() {
    this.$store.dispatch("loadAvailableLanguages");
  }
};
</script>

<style lang="scss" scoped>
</style>