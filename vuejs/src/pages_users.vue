<template>
  <div class="content mx-5 my-4">
    <!-- Create Modal -->
    <b-modal v-model="createModal" centered hide-footer title="Create a User">
      <b-form ref="form" method="post" @submit.prevent="onCreate">
        <b-form-group label="Username"
          label-for="username-input"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
        >
          <b-form-input id="username-input" v-model="username" size="sm" required />
        </b-form-group>
        <b-form-group label="Email Address"
          label-for="email-input"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
        >
          <b-form-input id="email-input" v-model="email" type="email" size="sm" required />
        </b-form-group>
        <b-row class="px-3">
          <div class="ml-auto">
            <b-button class="mt-3" @click="createModal = false" size="sm">Cancel</b-button>
            <b-button type="submit" class="mt-3" variant="primary" size="sm">OK</b-button>
          </div>
        </b-row>
      </b-form>
    </b-modal>

    <!-- Delete Modal -->
    <b-modal v-model="deleteModal" centered title="Delete Confirmation">
      <p>Are you sure you want to delete <b class="text-danger">{{ userToDelete.username }}</b>?</p>
      <p>Please confirm by clicking <b>OK</b>.</p>
      <template #modal-footer>
        <b-button size="sm" @click="deleteModal = false">No</b-button>
        <b-button size="sm" variant="danger" @click="onDelete(userToDelete.id)">Yes</b-button>
      </template>
    </b-modal>

    <b-container fluid>
      <h3 class="text-dark align-self-center mt-2 mb-4">Users</h3>
      <!-- CARD -->
      <b-card class="shadow p-0" header-tag="header" footer-tag="footer">
        <!-- CARD HEADER -->
        <template #header>
          <div class="d-flex justify-content-between align-items-center">
            <p class="text-primary m-0 fw-bold">User List</p>
            <b-button variant="primary mr-3" size="sm" @click="createModal = true">
              <i class="fas fa-plus"></i>&nbsp;<span>Create</span>
            </b-button>
          </div>
        </template>

        <div class="mx-3 my-2">
          <!-- FILTER/SEARCH INPUT -->
          <b-row class="mb-4">
            <b-col lg="8" xl="5" class="my-1">
              <b-input-group size="sm">
                <b-form-input
                  id="filter-input"
                  v-model="filter"
                  type="search"
                  placeholder="Type to Search"
                ></b-form-input>

                <b-input-group-append>
                  <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                </b-input-group-append>
              </b-input-group>
            </b-col>
          </b-row>

          <!-- ALERT -->
          <b-alert v-model="showAlert" :variant="alertStatus" dismissible>
            {{ this.alertMessage }}
          </b-alert>

          <!-- TABLE -->
          <b-table
            bordered hover
            head-variant="light"
            :items="users"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"
            :filter="filter"
            :busy="isBusy"
            stacked="md"
            show-empty
            small
            sort-by="id"
            :sort-desc="true"
            @filtered="onFiltered"
          >

            <!-- LOADING DATA -->
            <template #table-busy>
              <div class="text-center text-primary my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>

            <!-- SET FIXED WIDTH COLUMNS -->
            <template #table-colgroup="scope">
              <col
                v-for="field in scope.fields"
                :key="field.key"
                :style="{ width: field.key === 'id' ? '30px' : '180px' }"
              >
            </template>

            <!-- CHECK EDITABLE FIELDS -->
            <!-- <template v-for="(field, index) in editableFields"
              v-slot:[`cell(${field.key})`]="{ value, item, field: {key, type} }">
              <template v-if="edit != item.id">{{ value }}</template>
              <b-form-input v-else v-model="item[key]" :type="type" :key="index" size="sm" />
            </template> -->

            <!-- SINCE EDITABLE IS ONLY USERNAME -->
            <template #cell(username)="{ value, item }">
              <template v-if="userToEdit != item.id">{{ value }}</template>
              <b-form-input v-else :value="item.username" size="sm"
                @input="updateUsername($event, item.id)" />
            </template>

            <!-- ACTION BUTTONS -->
            <template #cell(actions)="{ item: { id, username }}">
              <template v-if="userToEdit == id">
                <b-button variant="success" size="sm" @click="onEdit(id, username)">
                  <i class="fas fa-save"></i>&nbsp;
                  <span>Save</span>
                </b-button>
                <b-button variant="secondary" size="sm" @click="updateUserToEdit">
                  <i class="fas fa-ban"></i>&nbsp;
                  <span>Cancel</span>
                </b-button>
              </template>
              <b-button v-else variant="primary" size="sm" @click="onEdit(id, username)">
                <i class="fas fa-edit"></i>&nbsp;<span>Edit</span>
              </b-button>
              <b-button variant="danger" size="sm"
                @click="updateUserToDelete(username, id), deleteModal = true">
                <i class="fas fa-trash"></i>&nbsp;<span>Delete</span>
              </b-button>
            </template>

            <!-- DEFAULT EMPTY TEXTS -->
            <template #empty="scope">
              <div class="text-center">{{ scope.emptyText }}</div>
            </template>
            <template #emptyfiltered="scope">
              <div class="text-center">{{ scope.emptyFilteredText }}</div>
            </template>
          </b-table>
        </div>

        <!-- CARD FOOTER -->
        <template #footer>
          <div class="d-flex justify-content-between">
            <!-- PAGE STATUS -->
            <p class="align-self-center mb-0 small">
              Showing {{ ((currentPage - 1) * perPage) + 1 }} to
                <span v-if="totalRows < (currentPage * perPage)">{{ totalRows }}</span>
                <span v-else>{{ currentPage * perPage }}</span>
                of {{ totalRows }}
            </p>
            <!-- PAGINATION -->
            <b-pagination
              v-model="currentPage"
              :total-rows="totalRows"
              :per-page="perPage"
              aria-controls="my-table"
              class="my-0 mr-3"
              size="sm"
            ></b-pagination>
          </div>
        </template>
      </b-card>
    </b-container>
  </div>
</template>

<script>
import { adminFields } from '@/utils/tables'
import { mapState } from 'vuex'

export default {
  layout: 'admin',
  data() {
    return {
      fields: adminFields,

      // v-models, TODO: refactor not to use v-models
      currentPage: 1,
      filter: null,
      createModal: false,
      deleteModal: false,
      showAlert: false,
      username: '',
      email: '',
    }
  },
  methods: {
    onCreate() {
      this.$store.dispatch('admin/createUser', {
        username: this.username,
        email: this.email,
      })
      this.showAlert = true
      this.createModal = false
    },
    onEdit(id, username) {
      let userToUpdate = id
      if (id === this.$store.state.admin.userToEdit) {
        this.$store.dispatch('admin/editUser', {
          id: id,
          form: { username: username }
        })
        this.showAlert = true
        userToUpdate = null
      }
      this.$store.commit('admin/UPDATE_USER_TO_EDIT', userToUpdate)

    },
    onDelete(id) {
      this.$store.dispatch('admin/deleteUser', id)
      this.showAlert = true
      this.deleteModal = false
    },
    onFiltered(filteredItems) {
      this.$store.commit('admin/FILTER_USERS_TABLE', filteredItems)
      this.currentPage = 1
    },
    updateUsername(username, id) {
      this.$store.commit('admin/UPDATE_USERNAME', { username, id })
    },
    updateUserToEdit() {
      this.$store.commit('admin/UPDATE_USER_TO_EDIT', null)
    },
    updateUserToDelete(username, id) {
      this.$store.commit('admin/UPDATE_USER_TO_DELETE', { username, id })
    }
  },
  computed: {
    editableFields() {
      return this.fields.filter((field) => field.editable);
    },
    ...mapState('admin', {
      isBusy: 'isBusy',
      users: 'users',
      perPage: 'perPage',
      totalRows: 'totalRows',
      alertStatus: 'alertStatus',
      alertMessage: 'alertMessage',
      userToDelete: 'userToDelete',
      userToEdit: 'userToEdit',
    }),
  },
  mounted() {
    this.$store.dispatch('admin/getUsers')
  },
}
</script>
