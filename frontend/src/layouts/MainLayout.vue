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

        <div class="actions" v-if="this.$router.currentRoute.name != 'Home'">
            <q-btn color="white" flat icon="edit" v-if="this.selected.length > 0" @click="editMode"/>
            <q-btn color="white" flat icon="add" v-else @click="addMode"/>
            <q-btn color="white" flat label="Invoices" icon="save_alt" @click="exportCsv"/>
            <q-btn color="white" flat label="Customer" icon="save_alt" @click="exportCsvCust"/>
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
          <q-input :rules="[val => !!val || 'Field is required']" type='text' v-model="selectedItems[index]" :label="index" v-if="index == 'client'" clearable/>
          <q-input :rules="[val => !!val || 'Field is required']" type='datetime' stack-label :label="index" filled v-model="selectedItems[index]" mask="datetime"  v-else-if="index == 'created_at'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='date' stack-label :label="index" filled v-model="selectedItems[index]" mask="date"  v-else-if="index == 'invoice_date'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='number' v-model="selectedItems[index]" :label="index" v-else-if="index != 'id' && index != 'invoice_status'" clearable/>
          <q-select :rules="[val => !!val || 'Field is required']" style="width:70%" v-model="selectedItems[index]" :options="options" :label="index" v-if="index === 'invoice_status'"/>
        </q-item>
        <q-item>
          <q-btn color="primary" label="Update" @click="updateInvoice"/>
        </q-item>
      </q-list>
      <q-list v-else-if="this.selected">
        <q-item v-for="item in Object.keys(selected)" :key="item" >
          <q-input :rules="[val => !!val || 'Field is required']" type='text' v-model="selected[item]" :label="item" v-if="item == 'client'" clearable/>
            <q-input :rules="[val => !!val || 'Field is required']" type='date' stack-label :label="item" filled v-model="selected[item]" mask="date"  v-else-if="item == 'invoice_date'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='datetime' stack-label :label="item" filled v-model="selected[item]" mask="datetime"  v-else-if="item == 'created_at'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='number' v-model="selected[item]" :label="item" v-else-if="item != 'id' && item != 'invoice_status'" clearable/>
          <q-select :rules="[val => !!val || 'Field is required']" style="width:70%" v-model="selected[item]" :options="options" :label="item" v-if="item === 'invoice_status'"/>
        </q-item>
        <q-item>
          <q-btn color="primary" label="Add" @click="saveInvoice"/>
        </q-item>
      </q-list>
      <span v-if="this.error" style="color:red;font-weigth:bold"> {{errorMsg}} </span>
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
      rightDrawerOpenAdd: false,
      errorMsg: 'You cannot save the form not all data are filled',
      error: false,
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
          title: 'Invoice Items',
          caption: 'See all items for each invoice',
          icon: 'people',
          link: '/items'
        }
      ],
      options: ['paid', 'unpaid']
    }
  },
  mounted() {
     this.rightDrawerOpen = false
     this.rightDrawerOpenAdd = false
  },
  created() {
     this.rightDrawerOpen = false
     this.rightDrawerOpenAdd = false
  },
  computed: {
    ...mapState("invoice", ["selected","forPagination"]),
    selectedItems() {
      return this.selected[0]
    }
  },
  methods: {
    ...mapActions("invoice", ["updateItem","clearSelected","updateSelected","createItem","fetchPaginated","exportDataToCsv","exportDataCustomerCSV"]),
    editMode() {
      this.rightDrawerOpen = !this.rightDrawerOpen
      this.rightDrawerOpenAdd = false
    },
    updateInvoice() {
      this.error = false
      Object.keys(this.selected[0]).forEach(key => {
        if(this.selected[0][key] == "")
          this.error = true
      })

      if(!this.error)
        this.updateItem(this.selected[0])
    },
    addMode() {
      let data = 
        {
          "rows": {
            "client": "",
            "created_at": "",
            "invoice_amount": "",
            "invoice_amount_plus_vat" : "",
            "invoice_date": "",
            "invoice_status": "",
            "vat_rate": ""
          }
        }
      
      this.rightDrawerOpenAdd = !this.rightDrawerOpenAdd
      this.rightDrawerOpen = !this.rightDrawerOpen

      this.updateSelected(data)
    },
    saveInvoice() {
      this.error = false
      Object.keys(this.selected).forEach(key => {
        if(this.selected[key] == "")
          this.error = true
      })

      if(!this.error)
      {
        this.createItem(this.selected)
        this.fetchPaginated(this.forPagination)
      }
      
    },
    exportCsv() {
      this.exportDataToCsv()
    },
    exportCsvCust() {
      this.exportDataCustomerCSV()
    }
    
  }
}
</script>

<style scoped>

</style>
