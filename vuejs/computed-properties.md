# Computed Properties and Watchers

## Computed Properties

In-template expressions are very convenient, but they are meant for simple operations. Putting too much logic in your templates can make them bloated and hard to maintain. For example, if we have an object with a nested array:

```js
Vue.createApp({
  data() {
    return {
      author: {
        name: "John Doe",
        books: [
          "Vue 2 - Advanced Guide",
          "Vue 3 - Basic Guide",
          "Vue 4 - The Mystery",
        ],
      },
    };
  },
});
```

And we want to display different messages depending on if `author` already has some books or not

```html
<div id="computed-basics">
  <p>Has published books:</p>
  <span>{{ author.books.length > 0 ? 'Yes' : 'No' }}</span>
</div>
```

At this point, the template is no longer simple and declarative. You have to look at it for a second before realizing that it performs a calculation depending on `author.books`. The problem is made worse when you want to include this calculation in your template more than once.

That's why for complex logic that includes reactive data, you should use a **computed property**.

## Basic Example

```html
<template>
  <div id="computed-basics">
    <p>Has published books:</p>
    <span>{{ publishedBooksMessage }}</span>
  </div>
</template>

<script>
  export default() {
    data() {
      return {
        author: {
          name: 'John Doe',
          books: [
            'Vue 2 - Advanced Guide',
            'Vue 3 - Basic Guide',
            'Vue 4 - The Mystery'
          ]
        }
      }
    },
    computed: {
      // a computed getter
      publishedBooksMessage() {
        // `this` points to the vm instance
        return this.author.books.length > 0 ? 'Yes' : 'No'
      }
    }
  }
</script>
```

Here we have declared a computed property `publishedBooksMessage`. When the value of `books` array in the application data changes, you will see how `publishedBooksMessage` is changing accordingly.
