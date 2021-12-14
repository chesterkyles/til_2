export const state = () => ({
  users: [],
  totalRows: 1,
  perPage: 10,
  alertStatus: '',
  alertMessage: '',
  userToDelete: {},
  userToEdit: null,
  isBusy: true,
})

export const mutations = {
  SET_USERS: (state, users) => {
    state.users = users
    state.totalRows = users.length
    state.isBusy = false
  },
  STORE_USER: (state, user) => {
    state.users.push(user)
    state.alertStatus = 'success'
    state.alertMessage = 'A user has been successfully created'
  },
  UPDATE_USERNAME: (state, { username, id }) => {
    const index = state.users.findIndex(user => user.id === id)
    state.users[index].username = username
  },
  UPDATE_USER: (state) => {
    state.alertStatus = 'success'
    state.alertMessage = 'A user has been successfully updated!'
  },
  UPDATE_USER_TO_EDIT: (state, user) => {
    state.userToEdit = user
  },
  DELETE_USER: (state, id) => {
    state.alertStatus = 'success'
    state.alertMessage = 'A user has been successfully deleted!'
    state.userToDelete = {}

    const index = state.users.findIndex(user => user.id === id)
    if (~index) state.users.splice(index, 1)
    state.totalRows = state.users.length
  },
  UPDATE_USER_TO_DELETE: (state, { username, id }) => {
    state.userToDelete = {
      id: id,
      username: username
    }
  },
  FILTER_USERS_TABLE: (state, filteredItems) => {
    state.totalRows = filteredItems.length
  },
  ERROR_RESPONSE: (state, action) => {
    state.alertStatus = 'danger'
    if (action == 'create') {
      state.alertMessage = 'There is an error in creating a user'
    } else if (action == 'edit') {
      state.alertMessage = 'There is an error in updating a user'
    } else if (action == 'delete') {
      state.alertMessage = 'There is an error in deleting a user'
    }
  },
}

export const actions = {
  getUsers({ commit }) {
    this.$axios.get('/api/agencies/users')
      .then(response => {
        commit('SET_USERS', response.data)
      })
  },
  createUser({ commit }, form) {
    this.$axios.post('/api/agencies/users', form)
      .then(response => {
        commit('STORE_USER', response.data)
      })
      .catch(error => {
        console.log(error)
        commit('ERROR_RESPONSE', 'create')
      })
  },
  editUser({ commit }, { id, form }) {
    this.$axios.put(`/api/agencies/users/${id}`, form)
      .then(commit('UPDATE_USER'))
      .catch(error => {
        console.log(error)
        commit('ERROR_RESPONSE', 'edit')
      })
  },
  deleteUser({ commit }, id ) {
    this.$axios.delete(`/api/agencies/users/${id}`)
      .then(commit('DELETE_USER', id))
      .catch(error => {
        console.log(error)
        commit('ERROR_RESPONSE', 'delete')
      })
  }
}

export const getters = {
  //getters here!
}
