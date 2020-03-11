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
            <q-btn round color="white" flat icon="edit" v-if="this.selected.length >0 " @click="editMode"/>
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
      side="left"
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

    <q-drawer
      v-model="rightDrawerOpen"
      show-if-above
      bordered
      content-class="bg-grey-1"
      side="right"
      v-if="rightDrawerOpen"
    >
      <q-list v-if="this.selected.length > 0">
        <q-item v-for="(item, index) in selectedItems" :key="index" >
          <q-input v-model="selectedItems[index]" :label="index" v-if="index != 'id' && index != 'invoice_status'" clearable/>
          <q-select style="width:70%" v-model="selectedItems[index]" :options="options" :label="index" v-if="index === 'invoice_status'"/>
        </q-item>
        <q-item>
          <q-btn color="primary" label="save" @click="saveItem"/>
        </q-item>
      </q-list>
    </q-drawer>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink'
import { mapState, mapActions } from 'vuex'


export default {
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  data () {
    return {
      leftDrawerOpen: false,
      rightDrawerOpen: false,
      essentialLinks: [
        {
          title: 'Home',
          icon: 'home',
          link: '/home'
        },
        {
          title: 'Invoice Report',
          caption: 'Manage your invoices',
          icon: 'settings',
          link: '/invoice'
        },
        {
          title: 'Customer Report',
          caption: 'Manage your customers',
          icon: 'people',
          link: '/customer'
        }
      ],
      options: ['paid', 'unpaid']
    }
  },
  mounted() {
     this.rightDrawerOpen = false
  },
  created() {
     this.rightDrawerOpen = false
  },
  computed: {
    ...mapState("invoice", ["selected"]),
    selectedItems() {
      return this.selected[0]
    }
  },
  methods: {
    editMode() {
      this.rightDrawerOpen = !this.rightDrawerOpen
    }
  }
}
</script>
