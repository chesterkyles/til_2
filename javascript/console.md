# Javascript Console Methods

## Reference

<https://www.tutorialstonight.com/js/javascript-console.php>

## Console in Javascript

The **console** is an object in javascript which is used for debugging and logging results.

**Console** is a global object so it is available in every scope. It is also available in the global scope of the browser window. You can use it as `window.console` or direct `console`. For example:

```js
window.console.log("Hello World!")
console.log(123)
console.log(10*5)
console.log(Math.PI)
```

## Console Methods

### log

`console.log()` method is the most commonly used method. It is used to output a message in the console. It can output all kinds of objects like `string`, `number`, `boolean`, `array`, `HTML elements`, etc.

```js
console.log(msg1, msg2, ..., obj1, obj2, ...)
```

### assert

`console.assert()` method asserts (or claim) a condition and if the condition is `false` then output a message in the console. If the assertion is `true`, then nothing is outputted.

```js
console.assert(assertion, message, obj1, obj2, ...)

// For Example
var num = 10
console.assert(num > 20, "Number is less than 20")

//-----------
// Output
//-----------
// Assertion failed: Number is less than 20
```

### clear

`console.clear()` method clears the console if it is allowed by the environment.

For example:

```js
console.log(10)
console.log("Hello World!")
console.clear()
```

### count

`console.count()` method logs the number of time `count()` method has been called.

The `count()` method accepts an argument which is a label to the output, it is logged every time `count()` function is called. It is optional, its default value is "`default`".




