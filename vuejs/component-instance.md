# Application and Component Instances

## References

<https://v3.vuejs.org/guide/instance.html#application-component-instances>

## Creating an Application Instance

```js
const app = Vue.createApp({
  /* options */
})
```

The application instance is used to register 'globals' that can be used by components within that application:

```js
const app = Vue.createApp({})
app.component('SearchInput', SearchInputComponent)
app.directive('focus', FocusDirective)
app.use(LocalePlugin)
```

## Root Component

The options passed to `createApp` are used to configure **the root component**. That component is used as the starting point for rendering when we **mount** the application.

For example, if we want to mount a Vue application into `<div id="app"></div>`, we should pass `#app`:

```js
const RootComponent = {
  /* options */
}
const app = Vue.createApp(RootComponent)
const vm = app.mount('#app')
```

## Component Instance Properties

```js
const app = Vue.createApp({
  data() {
    return { count: 4 }
  }
})

const vm = app.mount('#app')

console.log(vm.count) // => 4
```

There are various other component options that add user-defined properties to the component instance, such as `methods`, `props`, `computed`, `inject` and `setup`.

## Lifecycle Hooks

Each component instance goes through a series of initialization steps when it's created - for example, it needs to set up data observation, compile the template, mount the instance to the DOM, and update the DOM when data changes. Along the way, it also runs functions called **lifecycle hooks**, giving users the opportunity to add their own code at specific stages.

```js
Vue.createApp({
  data() {
    return { count: 1 }
  },
  created() {
    // `this` points to the vm instance
    console.log('count is: ' + this.count) // => "count is: 1"
  }
})
```

There are also other hooks which will be called at different stages of the instance's lifecycle, such as `mounted`, `updated`, and `unmounted`. All lifecycle hooks are called with their `this` context pointing to the current active instance invoking it.

The lifecycle diagram can be found here: <https://v3.vuejs.org/guide/instance.html#lifecycle-diagram>
