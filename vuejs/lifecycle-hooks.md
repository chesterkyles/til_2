# Lifecycle Hooks

## Lifecycle Diagram

 - <https://v3.vuejs.org/guide/instance.html#lifecycle-hooks>
 - <https://v3.vuejs.org/guide/instance.html#lifecycle-diagram>

## Methods/Functions

### beforeCreate

Called synchronously immediately after the instance has been initialized, before data observation and event/watcher setup.

### created

Called synchronously after the instance is created. At this stage, the instance has finished processing the options which means the following have been set up: data observation, computed properties, methods, watch/event callbacks. However, the mounting phase has not been started, and the `$el` property will not be available yet.

### beforeMount

Called right before the mounting begins: the `render` function is about to be called for the first time.

> This hook is not called during server-side rendering.

### mounted

Called after the instance has been mounted, where element, passed to `app.mount` is replaced by the newly created `vm.$el`. If the root instance is mounted to an in-document element, `vm.$el` will also be in-document when `mounted` is called.

Note that `mounted` does **not** guarantee that all child components have also been mounted. If you want to wait until the entire view has been rendered, you can use `vm.$nextTick` inside of `mounted`:

```js
mounted() {
  this.$nextTick(function () {
    // Code that will run only after the
    // entire view has been rendered
  })
}
```

> This hook is not called during server-side rendering.

### beforeUpdate

Called when data changes, before the DOM is patched. This is a good place to access the existing DOM before an update, e.g. to remove manually added event listeners.

> This hook is not called during server-side rendering, because only the initial render is performed server-side.

### updated

Called after a data change causes the virtual DOM to be re-rendered and patched.

The component's DOM will have been updated when this hook is called, so you can perform DOM-dependent operations here. However, in most cases you should avoid changing state inside the hook. To react to state changes, it's usually better to use a **computed property** or **watcher** instead.

Note that `updated` does not guarantee that all child components have also been re-rendered. If you want to wait until the entire view has been re-rendered, you can use `vm.$nextTick `inside of `updated`:

```js
updated() {
  this.$nextTick(function () {
    // Code that will run only after the
    // entire view has been re-rendered
  })
}
```

> This hook is not called during server-side rendering.

### activated

Called when a kept-alive component is activated.

> This hook is not called during server-side rendering.

Read more about dynamic components [here](https://v3.vuejs.org/guide/component-dynamic-async.html#dynamic-components-with-keep-alive).

### deactivated

Called when a kept-alive component is deactivated.

> This hook is not called during server-side rendering.

### beforeUnmount

Called right before a component instance is unmounted. At this stage the instance is still fully functional.

> This hook is not called during server-side rendering.

### unmounted

Called after a component instance has been unmounted. When this hook is called, all directives of the component instance have been unbound, all event listeners have been removed, and all child component instances have also been unmounted.

> This hook is not called during server-side rendering.

Read more about lifecycle hooks and other available methods or features [here](https://v3.vuejs.org/api/options-lifecycle-hooks.html).
