# File System Routing

## Introduction

Nuxt automatically generates the vue-router configuration based on your file tree of Vue files inside the pages directory. When you create a `.vue` file in the pages directory, you will have basic routing woring with no extra configuration ne4eded.

## Basic Routes

This file tree:

```
pages/
--| user/
-----| index.vue
-----| one.vue
--| index.vue
```

will automatically generate:

```js
router: {
  routes: [
    {
      name: "index",
      path: "/",
      component: "pages/index.vue",
    },
    {
      name: "user",
      path: "/user",
      component: "pages/user/index.vue",
    },
    {
      name: "user-one",
      path: "/user/one",
      component: "pages/user/one.vue",
    },
  ];
}
```

## Dynamic Routes

Sometimes it is not possible to know the name of the route such as when we make a call to an API to get a list of users or blog posts. We call these dynamic routes. To create a dynamic route you need to add an underscore `(_)` before the `.vue` file name or before the name of the directory. You can name the file or directory anything you want but you must prefix it with an underscore.

```
This file tree:

pages/
--| _slug/
-----| comments.vue
-----| index.vue
--| users/
-----| _id.vue
--| index.vue
```

will automatically generate:

```js
router: {
  routes: [
    {
      name: "index",
      path: "/",
      component: "pages/index.vue",
    },
    {
      name: "users-id",
      path: "/users/:id?",
      component: "pages/users/_id.vue",
    },
    {
      name: "slug",
      path: "/:slug",
      component: "pages/_slug/index.vue",
    },
    {
      name: "slug-comments",
      path: "/:slug/comments",
      component: "pages/_slug/comments.vue",
    },
  ];
}
```

## Nested Routes

Nuxt lets you create nested routes by using the children routes of vue-router. To define the parent component of a nested route, you need to create a Vue file with the same name as the directory which contains your children views.

> Don't forget to include the NuxtChild component inside the parent component (`.vue` file).

This file tree:

```
pages/
--| users/
-----| _id.vue
-----| index.vue
--| users.vue
```

will automatically generate:

```js
router: {
  routes: [
    {
      path: "/users",
      component: "pages/users.vue",
      children: [
        {
          path: "",
          component: "pages/users/index.vue",
          name: "users",
        },
        {
          path: ":id",
          component: "pages/users/_id.vue",
          name: "users-id",
        },
      ],
    },
  ];
}
```

## Dynamic Nested Routes

This is not a common scenario, but it is possible with Nuxt to have dynamic children inside dynamic parents.

This file tree:

```
pages/
--| _category/
-----| _subCategory/
--------| _id.vue
--------| index.vue
-----| _subCategory.vue
-----| index.vue
--| _category.vue
--| index.vue
```

will automatically generate:

```js
router: {
  routes: [
    {
      path: "/",
      component: "pages/index.vue",
      name: "index",
    },
    {
      path: "/:category",
      component: "pages/_category.vue",
      children: [
        {
          path: "",
          component: "pages/_category/index.vue",
          name: "category",
        },
        {
          path: ":subCategory",
          component: "pages/_category/_subCategory.vue",
          children: [
            {
              path: "",
              component: "pages/_category/_subCategory/index.vue",
              name: "category-subCategory",
            },
            {
              path: ":id",
              component: "pages/_category/_subCategory/_id.vue",
              name: "category-subCategory-id",
            },
          ],
        },
      ],
    },
  ];
}
```

## Unknown Dynamic Nested Routes

If you do not know the depth of your URL structure, you can use `_.vue` to dynamically match nested paths. This will handle requests that do not match a more specific route.

This file tree:

```
pages/
--| people/
-----| _id.vue
-----| index.vue
--| _.vue
--| index.vue
```

Will handle requests like this:

```
/ -> index.vue
/people -> people/index.vue
/people/123 -> people/_id.vue
/about -> _.vue
/about/careers -> _.vue
/about/careers/chicago -> _.vue
```

> Handling 404 pages is now up to the logic of the `_.vue` page.

## Extending the router

There are multiple ways to extend the routing with Nuxt:

- `router-extras-module` to customize the route parameters in the page
- `component@nuxtjs/router` to overwrite the Nuxt router and write your own `router.js` file
- Use the `router.extendRoutes` property in your `nuxt.config.js`

### Router property

The router property lets you customize the Nuxt router (vue-router).

```js
export default {
  router: {
    // customize the Nuxt router
  },
};
```

### extendRoutes

You may want to extend the routes created by Nuxt. You can do so via the `extendRoutes` option.

Example of adding a custom route:

```js
export default {
  router: {
    extendRoutes(routes, resolve) {
      routes.push({
        name: "custom",
        path: "*",
        component: resolve(__dirname, "pages/404.vue"),
      });
    },
  },
};
```

If you want to sort your routes, you can use the `sortRoutes(routes)` function from `@nuxt/utils`:

```js
import { sortRoutes } from "@nuxt/utils";
export default {
  router: {
    extendRoutes(routes, resolve) {
      // Add some routes here ...

      // and then sort them
      sortRoutes(routes);
    },
  },
};
```
