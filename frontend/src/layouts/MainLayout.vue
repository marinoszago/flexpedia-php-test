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

        <div class="actions" v-if="this.$router.currentRoute.name === 'Invoices'">
            <q-btn color="white" flat icon="edit" v-if="this.selected.length > 0" @click="editMode"/>
            <q-btn color="white" flat icon="add" v-else @click="addMode"/>
            <q-btn color="red" flat icon="delete" v-if="this.selected.length > 0" @click="deleteMode"/>
            <q-btn color="white" flat label="Invoices" icon="save_alt" @click="exportCsv"/>
            <q-btn color="white" flat label="Customer" icon="save_alt" @click="exportCsvCust"/>
        </div>
        <div class="actions" v-else-if="this.$router.currentRoute.name === 'Invoice Items'">
            <q-btn color="white" flat icon="edit" v-if="this.selectedInvoiceItems.length > 0" @click="editModeInvoice"/>
            <q-btn color="white" flat icon="add" v-else @click="addModeInvoiceItem"/>
            <q-btn color="red" flat icon="delete" v-if="this.selectedInvoiceItems.length > 0" @click="deleteMode"/>
            <q-btn color="white" flat label="Invoice Items" icon="save_alt" @click="exportCsvInvoiceItem"/>
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
      <q-list v-if="this.selected.length > 0 && this.selectedInvoiceItems.length == 0">
        <q-item v-for="(item, index) in selectedItems" :key="index" >
          <q-input :rules="[val => !!val || 'Field is required']" type='text' v-model="selectedItems[index]" :label="index" v-if="index == 'client'" clearable/>
          <q-input :rules="[val => !!val || 'Field is required']" type='datetime' stack-label :label="index" filled v-model="selectedItems[index]" mask="datetime"  v-else-if="index == 'created_at'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='date' stack-label :label="index" filled v-model="selectedItems[index]" mask="date"  v-else-if="index == 'invoice_date'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='number' v-model="selectedItems[index]" :label="index" v-else-if="index != 'id' && index != 'invoice_status'" clearable/>
          <q-select :rules="[val => !!val || 'Field is required']" style="width:70%" v-model="selectedItems[index]" :options="options" :label="index" v-if="index === 'invoice_status'"/>
        </q-item>
        <q-item>
          <q-btn color="primary" label="Update invoice" @click="updateInvoice"/>
        </q-item>
      </q-list>
      <q-list v-else-if="this.selected && this.selectedInvoiceItems.length == 0">
        <q-item v-for="item in Object.keys(selected)" :key="item" >
          <q-input :rules="[val => !!val || 'Field is required']" type='text' v-model="selected[item]" :label="item" v-if="item == 'client'" clearable/>
            <q-input :rules="[val => !!val || 'Field is required']" type='date' stack-label :label="item" filled v-model="selected[item]" mask="date"  v-else-if="item == 'invoice_date'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='datetime' stack-label :label="item" filled v-model="selected[item]" mask="datetime"  v-else-if="item == 'created_at'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='number' v-model="selected[item]" :label="item" v-else-if="item != 'id' && item != 'invoice_status'" clearable/>
          <q-select :rules="[val => !!val || 'Field is required']" style="width:70%" v-model="selected[item]" :options="options" :label="item" v-if="item === 'invoice_status'"/>
        </q-item>
        <q-item>
          <q-btn color="primary" label="Add invoice" @click="saveInvoice" />
        </q-item>
      </q-list>
      <q-list v-else-if="this.selectedInvoiceItems.length > 0 && this.selected.length == 0">
        <q-item v-for="(item, index) in selectedInvoiceItemsComp" :key="index" >
          <q-input :rules="[val => !!val || 'Field is required']" type='text' v-model="selectedInvoiceItemsComp[index]" :label="index" v-if="index == 'name'" clearable/>
          <q-input :rules="[val => !!val || 'Field is required']" type='datetime' stack-label :label="index" filled v-model="selectedInvoiceItemsComp[index]" mask="datetime"  v-else-if="index == 'created_at'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='number' v-model="selectedInvoiceItemsComp[index]" :label="index" v-else-if="index != 'id'" clearable/>
        </q-item>
        <q-item>
          <q-btn color="primary" label="Update invoice item" @click="updateItemInvoice"/>
        </q-item>
      </q-list>
      <q-list v-else-if="this.selectedInvoiceItems && this.selected.length == 0">
        <q-item v-for="item in Object.keys(selectedInvoiceItems)" :key="item" >
          <q-input :rules="[val => !!val || 'Field is required']" type='text' v-model="selectedInvoiceItems[item]" :label="item" v-if="item == 'name'" clearable/>
          <q-input :rules="[val => !!val || 'Field is required']" type='datetime' stack-label :label="item" filled v-model="selectedInvoiceItems[item]" mask="datetime"  v-else-if="item == 'created_at'"/>
          <q-input :rules="[val => !!val || 'Field is required']" type='number' v-model="selectedInvoiceItems[item]" :label="item" v-else-if="item != 'id'" clearable/>
        </q-item>
        <q-item>
          <q-btn color="primary" label="Add invoice item" @click="saveItemInvoice"/>
        </q-item>
      </q-list>
      <span v-if="this.error" style="color:red;font-weigth:bold"> {{errorMsg}} </span>
    </q-drawer>
    <q-page-container>
      <router-view />
    </q-page-container>
    <DialogConfirm v-if="this.dialogVisible" @deleteItemClicked="onDeleteItemClicked"/>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink'
import DialogConfirm from 'components/DialogConfirm'
import { mapState, mapActions } from 'vuex'


export default {
  name: 'MainLayout',

  components: {
    EssentialLink,
    DialogConfirm
  },

  data () {
    return {
      leftDrawerOpen: false,
      rightDrawerOpen: false,
      dialogConfirmOpen: false, 
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
          link: '/invoiceItems'
        }
      ],
      options: ['paid', 'unpaid']
    }
  },
  mounted() {
     this.rightDrawerOpen = false
     this.dialogConfirmOpen = false
  },
  created() {
     this.rightDrawerOpen = false
     this.dialogConfirmOpen = false
  },
  computed: {
    ...mapState("invoice", ["selected","forPagination"]),
    ...mapState("invoiceItem", ["selectedInvoiceItems","forPaginationInvoiceItem"]),
    ...mapState(["dialogVisible"]),
    selectedItems() {
      return this.selected[0]
    },
    selectedInvoiceItemsComp() {
      return this.selectedInvoiceItems[0]
    }
  },
  methods: {
    ...mapActions("invoice", ["updateItem","updateSelected","createItem",
                              "fetchPaginated","exportDataToCsv","exportDataCustomerCSV","clearSelected",
                              "deleteItem"]),
    ...mapActions("invoiceItem", ["updateInvoiceItem","updateSelectedInvoiceItem","createInvoiceItem",
                              "fetchPaginatedInvoiceItem","exportDataToCsvItems","clearSelectedInvoiceItem",
                              "deleteInvoiceItem"]),
    ...mapActions(["setDialogVisible"]),                          
    editMode() {
      this.clearSelectedInvoiceItem()
      this.rightDrawerOpen = !this.rightDrawerOpen
    },
    editModeInvoice() {
      this.clearSelected()
      this.rightDrawerOpen = !this.rightDrawerOpen
    },
    deleteMode() {
      this.setDialogVisible(!this.dialogVisible)
    },
    onDeleteItemClicked() {
      if(this.selected[0])
        this.deleteItem(this.selected[0])
      else if(this.selectedInvoiceItems[0])
        this.deleteInvoiceItem(this.selectedInvoiceItems[0])
      else{
        this.$q.notify({
          message:"Something went wrong",
          color: "negative"
        })
      }
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
    updateItemInvoice() {
      this.error = false
      Object.keys(this.selectedInvoiceItems[0]).forEach(key => {
        if(this.selectedInvoiceItems[0][key] == "")
          this.error = true
      })

      if(!this.error)
        this.updateInvoiceItem(this.selectedInvoiceItems[0])
    },
    addMode() {

      this.clearSelectedInvoiceItem()
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
      
      this.rightDrawerOpen = !this.rightDrawerOpen

      this.updateSelected(data)
    },
    addModeInvoiceItem() {
      this.clearSelected()
      let data = 
        {
          "rows": {
            "invoice_id": "",
            "name": "",
            "amount": "",
            "created_at" : ""
          }
        }
      
      this.rightDrawerOpen = !this.rightDrawerOpen

      this.updateSelectedInvoiceItem(data)
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
    saveItemInvoice() {
      this.error = false
      Object.keys(this.selectedInvoiceItems).forEach(key => {
        if(this.selectedInvoiceItems[key] == "")
          this.error = true
      })

      if(!this.error)
      {
        this.createInvoiceItem(this.selectedInvoiceItems)
        this.fetchPaginatedInvoiceItem(this.forPaginationInvoiceItem)
      }
      
    },
    exportCsv() {
      this.exportDataToCsv()
    },
    exportCsvCust() {
      this.exportDataCustomerCSV()
    },
    exportCsvInvoiceItem() {
      this.exportDataToCsvItems()
    }
    
  }
}
</script>

<style scoped>

</style>
