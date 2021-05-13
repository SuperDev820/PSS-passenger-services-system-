<template>
  <div class="wrapper">
    <notifications></notifications>
    <side-bar>
      <template slot="links">
        <!-- <sidebar-item
          :link="{
            name: 'Dashboards',
            icon: 'ni ni-shop text-primary',
          }"
        >
          <sidebar-item
            :link="{ name: 'Dashboard', path: '/dashboard' }"
          ></sidebar-item>
          <sidebar-item
            :link="{ name: 'Alternative', path: '/alternative' }"
          ></sidebar-item>
        </sidebar-item> -->

        <sidebar-item
          :link="{
            name: 'Users',
            icon: 'ni ni-archive-2 text-orange',
            path: '/admin/users'
          }">
        </sidebar-item>
        <sidebar-item
          :link="{
            name: 'Passengers',
            icon: 'ni ni-chart-pie-35 text-pink',
            path: '/admin/passengers'
          }">
        </sidebar-item>
        <sidebar-item
          :link="{
            name: 'Fleet',
            icon: 'ni ni-spaceship text-info',
            path: '/admin/fleet'
          }">
        </sidebar-item>
        <sidebar-item
          :link="{
            name: 'Flights',
            icon: 'ni ni-calendar-grid-58 text-red',
            path: '/admin/flights'
          }">
        </sidebar-item>
      </template>
    </side-bar>
    <div class="main-content">
      <navbar :type="$route.meta.navbarType"></navbar>

      <div @click="$sidebar.displaySidebar(false)">
        <fade-transition :duration="200" origin="center top" mode="out-in">
          <!-- your content here -->
          <router-view></router-view>
        </fade-transition>
      </div>
      <content-footer v-if="!$route.meta.hideFooter"></content-footer>
    </div>
  </div>
</template>
<script>
  /* eslint-disable no-new */
  import PerfectScrollbar from 'perfect-scrollbar';
  import 'perfect-scrollbar/css/perfect-scrollbar.css';

  function hasElement(className) {
    return document.getElementsByClassName(className).length > 0;
  }

  function initScrollbar(className) {
    if (hasElement(className)) {
      new PerfectScrollbar(`.${className}`);
    } else {
      // try to init it later in case this component is loaded async
      setTimeout(() => {
        initScrollbar(className);
      }, 100);
    }
  }

  import Navbar from './Navbar.vue';
  import ContentFooter from './ContentFooter.vue';
  import Content from './Content.vue';
  import { FadeTransition } from 'vue2-transitions';

  export default {
    components: {
      Navbar,
      ContentFooter,
      Content,
      FadeTransition
    },
    methods: {
      initScrollbar() {
        let isWindows = navigator.platform.startsWith('Win');
        if (isWindows) {
          initScrollbar('sidenav');
        }
      }
    },
    mounted() {
      this.initScrollbar()
    }
  };
</script>
<style lang="scss">
</style>
