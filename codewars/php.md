# Codewars - PHP

## Link

<https://www.codewars.com/users/chestercolita/completed_solutions>

## Split Strings

### Question

Complete the solution so that it splits the string into pairs of two characters. If the string contains an odd number of characters then it should replace the missing second character of the final pair with an underscore ('\_').

Examples:

```php
solution('abc') // should return ['ab', 'c_']
solution('abcdef') // should return ['ab', 'cd', 'ef']
```

### My Solution

```php
function solution($str) {
  $result = [];
  if (empty($str)) return $result;

  foreach (str_split($str, 2) as $split) {
    if (strlen($split) != 2) {
      $result[] = $split . '_';
      continue;
    }
    $result[] = $split;
  }
  return $result;
}
```

## Bit Counting

### Question

Write a function that takes an integer as input, and returns the number of bits that are equal to one in the binary representation of that number. You can guarantee that input is non-negative.

Example: The binary representation of `1234` is `10011010010`, so the function should return `5` in this case

### My Solution

```php
function countBits($n)
{
  $output = [];
  while ($n > 0) {
    $output[] = $n % 2;
    $n = floor($n / 2);
  }

  $binary = array_reverse($output);
  return array_count_values($binary)[1];
}
```

## Narcissistic Number

### Question

A [Narcissistic Number](https://en.wikipedia.org/wiki/Narcissistic_number) is a positive number which is the sum of its own digits, each raised to the power of the number of digits in a given base. In this Kata, we will restrict ourselves to decimal (base 10).

For example, take 153 (3 digits), which is narcisstic:

```
1^3 + 5^3 + 3^3 = 1 + 125 + 27 = 153
```

and 1652 (4 digits), which isn't:

```
1^4 + 6^4 + 5^4 + 2^4 = 1 + 1296 + 625 + 16 = 1938
```

The Challenge:

Your code must return true or false (not 'true' and 'false') depending upon whether the given number is a Narcissistic number in base 10. This may be True and False in your language, e.g. PHP.

Error checking for text strings or other invalid inputs is not required, only valid positive non-zero integers will be passed into the function.

### My Solution

```php
function narcissistic(int $value): bool {
  $valueInString = (string) $value;
  $numDigits = strlen($valueInString);

  $sum = 0;
  foreach (str_split($valueInString) as $digit) {
    $sum += pow((int) $digit, $numDigits);
  }

  return $sum == $value;
}
```

## Diophantine Equation

### Question

In mathematics, a [Diophantine equation](https://en.wikipedia.org/wiki/Diophantine_equation) is a polynomial equation, usually with two or more unknowns, such that only the integer solutions are sought or studied.

In this kata we want to find all integers `x, y` (`x >= 0, y >= 0`) solutions of a diophantine equation of the form:

```
x2 - 4 * y2 = n
```

(where the unknowns are `x` and `y`, and `n` is a given positive number) in decreasing order of the positive xi.

If there is no solution return `[]` or `"[]"` or `""`.

Examples:

```
solEquaStr(90005) --> "[[45003, 22501], [9003, 4499], [981, 467], [309, 37]]"

solEquaStr(90002) --> "[]"
```

Hint:

```
x2 - 4 * y2 = (x - 2*y) * (x + 2*y)
```

### My Solution

```php
function solequa($n) {
  $result = [];
  foreach (range(1, ceil(sqrt($n))) as $sol1) {
    $sol2 = $n / (int)$sol1;
    if (!is_int($sol2)) continue;

    $x = ($sol2 + (int)$sol1)/2;
    $y = ($sol2 - (int)$sol1)/4;

    if (!is_int($x) || !is_int($y)) continue;

    $result[] = [$x, $y];
  }
  return $result;
}
```

## Duplicate Encoder

### Question

The goal of this exercise is to convert a string to a new string where each character in the new string is `"("` if that character appears only once in the original string, or `")"` if that character appears more than once in the original string. Ignore capitalization when determining if a character is a duplicate.

Examples:

```
"din"      =>  "((("
"recede"   =>  "()()()"
"Success"  =>  ")())())"
"(( @"     =>  "))(("
```

### My Solution

```php
function duplicate_encode($word) {
  $array = str_split(strtolower($word));
  $counts = array_count_values($array);

  foreach ($array as $key => $char) {
    $array[$key] = ($counts[$char] > 1) ? ')' : '(';
  }

  return implode($array);
}
```
