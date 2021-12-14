# Codewars - Javascript

## Take a Ten Minute Walk

### Question

You live in the city of Cartesia where all roads are laid out in a perfect grid. You arrived ten minutes too early to an appointment, so you decided to take the opportunity to go for a short walk. The city provides its citizens with a Walk Generating App on their phones -- everytime you press the button it sends you an array of one-letter strings representing directions to walk (eg. ['n', 's', 'w', 'e']). You always walk only a single block for each letter (direction) and you know it takes you one minute to traverse one city block, so create a function that will return true if the walk the app gives you will take you exactly ten minutes (you don't want to be early or late!) and will, of course, return you to your starting point. Return false otherwise.

> "Note: you will always receive a valid array containing a random assortment of direction letters ('n', 's', 'e', or 'w' only). It will never give you an empty array (that's not a walk, that's standing still!)."

### My Solutions

```js
function isValidWalk(walk) {
  if (walk.length != 10) return false;

  const north = walk.filter((dir) => dir == "n").length;
  const south = walk.filter((dir) => dir == "s").length;

  if (north != south) return false;

  const east = walk.filter((dir) => dir == "e").length;
  const west = walk.filter((dir) => dir == "w").length;

  if (east != west) return false;

  return true;
}
```

```js
function isValidWalk(walk) {
  if (walk.length != 10) return false;

  let point = 0;
  const vert = 1;
  const horz = 2;

  walk.forEach((dir) => {
    if (dir == "n") point = point + vert;
    if (dir == "s") point = point - vert;
    if (dir == "e") point = point + horz;
    if (dir == "w") point = point - horz;
  });

  return point == 0 ? true : false;
}
```
