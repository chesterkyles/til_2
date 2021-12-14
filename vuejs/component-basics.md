# Component Basics

## Basic Example

```js
// Create a Vue application
const app = Vue.createApp({})

// Define a new global component called button-counter
app.component('button-counter', {
  data() {
    return {
      count: 0
    }
  },
  template: `
    <button @click="count++">
      You clicked me {{ count }} times.
    </button>`
})
```

Components are reusable instances with a name: in this case, `<button-counter>`.

```js
<div id="components-demo">
  <button-counter></button-counter>
</div>
```

Components can be reused as many time as you want:

```js
<div id="components-demo">
  <button-counter></button-counter>
  <button-counter></button-counter>
  <button-counter></button-counter>
</div>
```

## Organizing Components

For example, you might have components for a header, sidebar, and content area, each typically containing other components for navigation links, blog posts, etc.

To use these components in templates, they must be registered so that Vue knows about them. There are two types of component registration: **global** and **local**. So far, we've only registered components globally, using the `component` method of our app:

```js
const app = Vue.createApp({})

app.component('my-component-name', {
  // ... options ...
})
```

Globally registered components can be used in the template of any component within the app.

## Passing Data to Child Components with Props

Props are custom attributes you can register on a component. To pass a title to our blog post component, we can include it in the list of props this component accepts, using the `props` option:

```js
const app = Vue.createApp({})

app.component('blog-post', {
  props: ['title'],
  template: `<h4>{{ title }}</h4>`
})

app.mount('#blog-post-demo')
```

When a value is passed to a prop attribute, it becomes a property on that component instance. The value of that property is accessible within the template, just like any other component property.

In a typical app, however, you'll likely have an array of posts in `data`:

```js
const App = {
  data() {
    return {
      posts: [
        { id: 1, title: 'My journey with Vue' },
        { id: 2, title: 'Blogging with Vue' },
        { id: 3, title: 'Why Vue is so fun' }
      ]
    }
  }
}

const app = Vue.createApp(App)

app.component('blog-post', {
  props: ['title'],
  template: `<h4>{{ title }}</h4>`
})

app.mount('#blog-posts-demo')
```

Then want to render a component for each one:

```html
<div id="blog-posts-demo">
  <blog-post
    v-for="post in posts"
    :key="post.id"
    :title="post.title"
  ></blog-post>
</div>
```

Above, you'll see that we can use `v-bind` to dynamically pass props. This is especially useful when you don't know the exact content you're going to render ahead of time.

## Listening to Child Components Events

For example, enlarging text by clicking a button in the child component. When we click on the button, we need to communicate to the parent that it should enlarge the text of all posts. To solve this problem, component instances provide a custom events system. The parent can choose to listen to any event on the child component instance with `v-on` or `@`, just as we would with a native DOM event:

```html
<blog-post ... @enlarge-text="postFontSize += 0.1"></blog-post>
```

Then the child component can emit an event on itself by calling the built-in `$emit` method, passing the name of the event:

```html
<button @click="$emit('enlargeText')">
  Enlarge text
</button>
```

We can list emitted events in the component's `emits` option:

```js
app.component('blog-post', {
  props: ['title'],
  emits: ['enlargeText']
})
```

### Emitting a Value with an Event

It's sometimes useful to emit a specific value with an event. For example, we may want the `<blog-post>` component to be in charge of how much to enlarge the text by. In those cases, we can pass a second parameter to `$emit` to provide this value:

```html
<button @click="$emit('enlargeText', 0.1)">
  Enlarge text
</button>
```

### Using `v-model` on Components

```html
<input v-model="searchText" />
```

is also equivalent to:

```html
<input :value="searchText" @input="searchText = $event.target.value" />
```

When used on a component, `v-model` instead does this:

```html
<custom-input
  :model-value="searchText"
  @update:model-value="searchText = $event"
></custom-input>
```

For Vue version 3, for this to actually work though, the `<input>` inside the component must:

- Bind the `value` attribute to the `modelValue` prop
- On `input`, emit an `update:modelValue` event with the new value

Read here for version 2: <https://vuejs.org/v2/guide/components.html>

Here's that in action:

```js
app.component('custom-input', {
  props: ['modelValue'],
  emits: ['update:modelValue'],
  template: `
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    >
  `
})
```

Now `v-model` should work perfectly with this component:

```html
<custom-input v-model="searchText"></custom-input>
```

## Content Distribution with Slots

```html
<alert-box>
  Something bad happened.
</alert-box>
```

This can be achieved using Vue's custom `<slot>` element:

```js
app.component('alert-box', {
  template: `
    <div class="demo-alert-box">
      <strong>Error!</strong>
      <slot></slot>
    </div>
  `
})
```
