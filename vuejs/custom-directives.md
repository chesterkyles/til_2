# Custom Directives

In addition to the default set of directives shipped in core (like `v-model` or `v-show`), Vue also allows you to register your own custom directives. Note that in Vue, the primary form of code reuse and abstraction is components - however, there may be cases where you need some low-level DOM access on plain elements, and this is where custom directives would still be useful.

## Reference

https://v3.vuejs.org/guide/custom-directive.html#intro

## Example

### Focusing on an input element

Our goal here is to have an input element gain focus when the page loads. In order to accomplish this, let us build the following directive:

```js
const app = Vue.createApp({});
// Register a global custom directive called `v-focus`
app.directive("focus", {
  // When the bound element is mounted into the DOM...
  mounted(el) {
    // Focus the element
    el.focus();
  },
});
```

If you want to register a directive locally instead, components also accept a `directives` option:

```js
directives: {
  focus: {
    // directive definition
    mounted(el) {
      el.focus()
    }
  }
}
```

Then in a template, you can use the new `v-focus` attribute on any element, like this:

```html
<input v-focus />
```

## Usage on Components

When used on components, custom directive will always apply to component's root node, similarly to **non-prop attributes**.

```html
<my-component v-demo="test"></my-component>
```

```js
app.component("my-component", {
  template: `
    <div> // v-demo directive will be applied here
      <span>My component content</span>
    </div>
  `,
});
```

Unlike attributes, directives can't be passed to a different element with `v-bind="$attrs"`.

With **fragments** support (in Vue3), components can potentially have more than one root node. When applied to a multi-root component, directive will be ignored and the warning will be thrown.
