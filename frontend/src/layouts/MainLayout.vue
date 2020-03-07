<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />

        <q-toolbar-title>
          {{this.$router.currentRoute.name}}
        </q-toolbar-title>

        <div v-if="this.$router.currentRoute.name != 'Home'">
            <q-btn round color="white" flat icon="add"/>
            <q-btn round color="white" flat icon="save_alt"/>
        </div>
        <div v-else>Written by Marinos Zagkotsis</div>
      </q-toolbar>
    </q-header>
    
    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      content-class="bg-grey-1"
    >
      <q-list>
        <q-item-label
          header
          class="text-grey-8"
        >
          Navigation
        </q-item-label>
        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink'

export default {
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  data () {
    return {
      leftDrawerOpen: false,
      essentialLinks: [
        {
          title: 'Home',
          icon: 'home',
          link: '/home'
        },
        {
          title: 'Invoice System',
          caption: 'Manage your invoices',
          icon: 'settings',
          link: '/invoice'
        }
      ]
    }
  },
  mounted() {
  }
}
</script>
