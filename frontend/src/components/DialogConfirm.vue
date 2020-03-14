<template>
    <div class="q-pa-md q-gutter-sm">
        <q-dialog v-model="confirmDialog" persistent @before-hide="beforeHide">
            <q-card>
                <q-card-section class="row items-center">
                    <q-avatar icon="warning" color="red" text-color="white" />
                    <span class="q-ml-sm">Are you sure you want to delete this item?</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup @click="hide"/>
                    <q-btn flat label="Delete" color="red" v-close-popup @click="deleteitem"/>
            </q-card-actions>
        </q-card>
    </q-dialog>
    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex'
export default {
  name: 'DialogConfirm',
  props: {
  },
  methods: {
      ...mapActions(["setDialogVisible"]),
      beforeHide() {
          let notVisible = false
          this.setDialogVisible(notVisible)
      },
      deleteitem() {
          this.$emit('deleteItemClicked', true)
      },
      hide() {
          let notVisible = false
          this.setDialogVisible(notVisible)
      }
  },
  computed: {
      ...mapState(["dialogVisible"]),
      confirmDialog: {
          get() {
              return this.dialogVisible
          },
          set() {

          }
      }
  }
}
</script>
